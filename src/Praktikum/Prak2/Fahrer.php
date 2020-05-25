<?php // UTF-8 marker äöüÄÖÜß€
require_once "./Page.php";

class Fahrer extends Page {

    protected function __constructor() {
        parent::__constructor();
    }

    public function __destructor() {
        parent::__destructor();
    }

    protected function processReceivedData() {
        parent::processReceivedData();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            return;
        }

        if (isset($_POST['status']) && is_numeric($_POST['status'])) {
            $status = $_POST['status'];
        } else {
            return;
        }

        $query = <<<SQL
        UPDATE ordered_articles
        SET status = ?
        WHERE id = ?
        SQL;

        $stmt = $this->_database->prepare($query);
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();

        header('Location: http://localhost/Praktikum/Prak2/Fahrer.php');
    }

    protected function getViewData() {
        $orderedArticles = array();
        //$sql = "SELECT ordered_articles.id, ordered_articles.f_article_id, ordered_articles.f_order_id, ordered_articles.status, article.name, ordering.address FROM ordered_articles, ordering LEFT JOIN article ON ordered_articles.f_article_id = article.id LEFT JOIN ordered_articles ON ordered_articles.f_order_id = ordering.id";
        $sql = "SELECT ordered_articles.id, f_article_id, f_order_id, status, address FROM ordered_articles LEFT JOIN ordering ON f_order_id = ordering.id";
        $result = $this->_database->query($sql);
        if(!$result)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($row = $result->fetch_assoc()) {
            $orderedArticles[] = $row;
        }
        $result->free();
        return $orderedArticles;
    }

    protected function generateView() {
        $orderedArticles = $this->getViewData();
        $this->generatePageHeader('Fahrer');

        echo <<<EOT
        <meta http-equiv="refresh" content="5" > 
        <header>
            <h1>Fahrer</h1>
        </header>
        EOT;

        foreach ($orderedArticles as $orderedArticle) {
            $status = intval($orderedArticle['status']);
            if ($status < 3)
                continue;

            echo "<form action=\"Fahrer.php\" method=\"post\">";
            echo "<input type='hidden' name='id' value={$orderedArticle['id']} />";

            switch ($orderedArticle["f_article_id"]) {
                case 1:
                    echo <<<EOT
                        <section>
                        <h3>Bestellung {$orderedArticle["id"]}: Pizza Salami - Address: {$orderedArticle["address"]}</h3>
                        <p>Status:</p>
                    EOT;
                    break;

                case 2:
                    echo <<<EOT
                        <section>
                        <h3>Bestellung {$orderedArticle["id"]}: Pizza Vegetaria - Address: {$orderedArticle["address"]}</h3>
                        <p>Status:</p>
                    EOT;
                    break;

                case 3:
                    echo <<<EOT
                        <section>
                        <h3>Bestellung {$orderedArticle["id"]}: Pizza Spinat-Hünchen - Address: {$orderedArticle["address"]}</h3>
                        <p>Status:</p>
                    EOT;
                    break;
            }

            $isChecked = $status == 3 ? 'checked' : null;

            echo <<<EOT
            
            <label>
                <input type="radio" name="status" value=3 {$isChecked} /> 
                Fertig
            </label>
            EOT;

            $isChecked = $status == 4 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=4 {$isChecked} /> 
                Unterwegs
            </label>
            EOT;

            $isChecked = $status == 5 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=5 {$isChecked} /> 
                Geliefert
            </label> 
            EOT;

            echo <<<EOT
            </section>
            EOT;

            echo "<br>";
            echo "<input type=\"submit\" value=\"Ändern\"/>";
            echo "</form>";
        }


        $this->generatePageFooter();
    }

    public static function main()
    {
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Fahrer::main();



//$sql = "SELECT ordered_articles.id, ordered_articles.f_article_id, ordered_articles.f_order_id, ordered_articles.status, article.name, ordering.address FROM ordered_articles, ordering LEFT JOIN article ON ordered_articles.f_article_id = article.id LEFT JOIN ordered_articles ON ordered_articles.f_order_id = ordering.id";

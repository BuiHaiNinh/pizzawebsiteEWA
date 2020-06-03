<?php // UTF-8 marker äöüÄÖÜß€
require_once "./Page.php";

class Kunde extends Page {

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

        header('Location: http://localhost/Praktikum/Prak2/Kunde.php');
    }

    protected function getViewData() {
        $orderedArticles = array();
        $sql = "SELECT ordered_articles.id, f_article_id, f_order_id, status, name FROM ordered_articles LEFT JOIN article ON f_article_id = article.id";
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
        $this->generatePageHeader('Kunde');
        header("Refresh: 5; url=http://localhost/Praktikum/Prak2/Kunde.php");

        echo <<<EOT
        <header>
            <h1>Kunde (Lieferstatus)</h1>
        </header>
        EOT;

        foreach ($orderedArticles as $orderedArticle) {
            $status = intval($orderedArticle['status']);
            switch ($status){
                case 0:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Bestellt</p>
                    EOT;
                    break;

                case 1:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Im Ofen</p>
                    EOT;
                    break;

                case 2:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Gebackt fertig </p>
                    EOT;
                    break;

                case 3:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Gebackt fertig. Warte zum liefern</p>
                    EOT;
                    break;

                case 4:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Unterwegs</p>
                    EOT;
                    break;

                case 5:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: Geliefert</p>
                    EOT;
                    break;
            }
        }


        $this->generatePageFooter();
    }

    public static function main()
    {
        try {
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Kunde::main();


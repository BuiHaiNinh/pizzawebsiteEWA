<?php // UTF-8 marker äöüÄÖÜß€
require_once "./Page.php";

class Fahrer extends Page
{

    protected function __constructor()
    {
        parent::__construct();
    }

    public function __destructor()
    {
        parent::__destruct();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        if (isset($_POST['f_order_id']) && is_numeric($_POST['f_order_id'])) {
            $f_order_id = $_POST['f_order_id'];
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
        WHERE f_order_id = ?
        SQL;

        $stmt = $this->_database->prepare($query);
        $stmt->bind_param('si', $status, $f_order_id);
        $stmt->execute();

        header('Location: http://localhost/Praktikum/Prak2/Fahrer.php');
    }

    protected function getViewData()
    {
        $orderedArticles = array();
        $sql = <<<SQL
        SELECT oa.id, oa.f_article_id, oa.f_order_id, oa.status, od.address, a.name, a.price
        FROM ordered_articles oa 
            LEFT JOIN ordering od ON oa.f_order_id = od.id 
            LEFT JOIN article a ON oa.f_article_id = a.id
        SQL;

        $result = $this->_database->query($sql);
        if (!$result)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($row = $result->fetch_assoc()) {
            $orderedArticles[] = $row;
        }
        $result->free();

        $bestellungen = array();
        foreach ($orderedArticles as $row) {
            $bestellungen[$row["f_order_id"]][] = $row;
        }

        return $bestellungen;
    }

    protected function generateView()
    {
        $bestellungen = $this->getViewData();
        $this->generatePageHeader('Fahrer');
        header("Refresh: 5; url=http://localhost/Praktikum/Prak2/Fahrer.php");

        echo <<<EOT
        <meta http-equiv="refresh" content="5" > 
        <header>
            <h1>Fahrer</h1>
        </header>
        EOT;

        if (sizeof($bestellungen) == 0) {
            echo "<p>Es gibt in diesem Moment keine Bestellungen !!!</p>>";
            return;
        }

        foreach ($bestellungen as $orderedArticles) {

            $bestellungen = array_filter($orderedArticles, function ($value) {
                $status = $value['status'];
                return ($status >= 2 && $status <= 3);
            });

            if (sizeof($bestellungen) == 0) {
                continue;
            }

            $price = array_reduce($bestellungen, function ($value, $i) {
                $value += $i['price'];
                return $value;
            }, 0);

            echo "<h3>Bestellung {$orderedArticles[0]["f_order_id"]}: {$orderedArticles[0]['address']}. Summe: {$price} EURO</h3>";

            $status = intval($orderedArticles[0]['status']);

            echo "<form action=\"Fahrer.php\" method=\"post\">";
            echo "<input type='hidden' name='f_order_id' value={$orderedArticles[0]["f_order_id"]} />";

            foreach ($orderedArticles as $orderedArticle) {
                echo <<<EOT
                <section>
                <h5>Pizza Nr.{$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</h5>
                <!--<p>Status:</p>-->
            EOT;
            }

            echo "<p>Status:</p>";

            $isChecked = $status == 2 ? 'checked' : null;
            echo <<<EOT
            <label>
                <input type="radio" name="status" value=2 {$isChecked} /> 
                Gebackt fertig. Warte zum liefern
            </label>
            EOT;

            $isChecked = $status == 3 ? 'checked' : null;
            echo <<<EOT
            <label>
                <input type="radio" name="status" value=3 {$isChecked} /> 
                Unterwegs
            </label>
            EOT;

            $isChecked = $status == 4 ? 'checked' : null;
            echo <<<EOT
            <label>
                <input type="radio" name="status" value=4 {$isChecked} /> 
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

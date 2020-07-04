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

        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            return;
        }

        // is_int()
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

        header('Location: http://localhost/Praktikum/Prak4/Fahrer.php');
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
        header("Refresh: 5; url=http://localhost/Praktikum/Prak4/Fahrer.php");

        echo <<<EOT
        <header>
            <h1 id="title">Fahrer</h1>
        </header>
        EOT;

        foreach ($bestellungen as $orderedArticles) {

            $bestellungen = array_filter($orderedArticles, function ($value) {
                $status = $value['status'];
                return ($status >= 2 && $status <= 3);
            });

            if (sizeof($bestellungen) == 0)
                continue;

            $price = array_reduce($bestellungen, function ($value, $i) {
                $value += $i['price'];
                return $value;
            }, 0);

            echo "<h3>Bestellung {$orderedArticles[0]["f_order_id"]}:"." - ". htmlspecialchars($orderedArticles[0]['address'])." - "." Summe: {$price}</h3>";

            foreach ($orderedArticles as $orderedArticle) {
                $status = intval($orderedArticle['status']);

                echo "<form action=\"Fahrer.php\" method=\"post\">";
                echo "<input type='hidden' name='id' value={$orderedArticle['id']} />";

                echo <<<EOT
                <section>
                <h5>Pizza Nr.{$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</h5>
                <p>Status:</p>
            EOT;

                $isChecked = $status == 2 ? 'checked' : null;
                echo <<<EOT
            <label>
                <input type="radio" name="status" value=2 {$isChecked} onclick="fahrerSubmit(this)" /> 
                Gebackt fertig. Warte zum liefern
            </label><br>
            EOT;

                $isChecked = $status == 3 ? 'checked' : null;
                echo <<<EOT
            <label>
                <input type="radio" name="status" value=3 {$isChecked}  onclick="fahrerSubmit(this)" /> 
                Unterwegs
            </label><br>
            EOT;

                $isChecked = $status == 4 ? 'checked' : null;
                echo <<<EOT
            <label>
                <input type="radio" name="status" value=4 {$isChecked}  onclick="fahrerSubmit(this)"  /> 
                Geliefert
            </label><br>
            EOT;

                echo <<<EOT
            </section>
            EOT;
                echo "<br>";
                echo "</form>";
            }
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


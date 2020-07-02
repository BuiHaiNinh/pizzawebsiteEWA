<?php // UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Baecker extends Page
{

    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
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

        header('Location: http://localhost/Praktikum/Prak4/Baecker.php');
    }

    protected function getViewData()
    {
        $orderedArticles = array();
        $sql = "SELECT ordered_articles.id, f_article_id, f_order_id, status, name FROM ordered_articles LEFT JOIN article ON f_article_id = article.id";
        $result = $this->_database->query($sql);
        if (!$result)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($row = $result->fetch_assoc()) {
            $orderedArticles[] = $row;
        }
        $result->free();
        return $orderedArticles;
    }

    protected function generateView()
    {
        $orderedArticles = $this->getViewData();
        $this->generatePageHeader('Bäcker');
        //header("Refresh: 5; url=http://localhost/Praktikum/Prak4/Baecker.php");

        echo <<<EOT
        <header>
            <h1 id="title">Bäcker</h1>
        </header>
        EOT;

        if (sizeof($orderedArticles) == 0) {
            echo "<p>Es gibt in diesem Moment keine Bestellungen</p>>";
            return;
        }

        echo "<table>";
        foreach ($orderedArticles as $orderedArticle) {
            $status = intval($orderedArticle['status']);
            if ($status >= 3)
                continue;
            echo "<tr>";
            echo "<td class='Baecker_list'>";
            echo "<form id='formid' action=\"Baecker.php\" method=\"post\">";
            echo "<input type='hidden' name='id' value={$orderedArticle['id']} />";

            echo <<<EOT
            <section>
                <h2>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</h2>
                <p>Status:</p>
            EOT;

            $isChecked = $status == 0 ? 'checked' : null;

            echo <<<EOT
            
            <label>
                <input type="radio" name="status" value=0 {$isChecked} onclick="baeckerSubmit(this)" /> 
                Bestellt
            </label>
            EOT;

            $isChecked = $status == 1 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=1 {$isChecked} onclick="baeckerSubmit(this)"/> 
                Im Ofen
            </label>
            EOT;

            $isChecked = $status == 2 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=2 {$isChecked} onclick="baeckerSubmit(this)" /> 
                Fertig
            </label> 
            EOT;

            echo <<<EOT
            </section>
            EOT;

            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</nav>";

        $this->generatePageFooter();
    }

    public static function main()
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Baecker::main();

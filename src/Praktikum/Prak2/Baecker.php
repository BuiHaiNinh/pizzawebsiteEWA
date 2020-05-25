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

        header('Location: http://localhost/Praktikum/Prak2/Baecker.php');
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

        echo <<<EOT
        <meta http-equiv="refresh" content="5" > 
        <header>
            <h1>Bäcker</h1>
        </header>
        EOT;

        foreach ($orderedArticles as $orderedArticle) {
            $status = intval($orderedArticle['status']);
            if ($status >= 3)
                continue;

            echo "<form action=\"Baecker.php\" method=\"post\">";
            echo "<input type='hidden' name='id' value={$orderedArticle['id']} />";

            echo <<<EOT
            <section>
                <h2>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</h2>
                <p>Status:</p>
            EOT;

            $isChecked = $status == 0 ? 'checked' : null;

            echo <<<EOT
            
            <label>
                <input type="radio" name="status" value=0 {$isChecked} /> 
                Bestellt
            </label>
            EOT;

            $isChecked = $status == 1 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=1 {$isChecked} /> 
                Im Ofen
            </label>
            EOT;

            $isChecked = $status == 2 ? 'checked' : null;

            echo <<<EOT
            <label>
                <input type="radio" name="status" value=2 {$isChecked} /> 
                Fertig
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

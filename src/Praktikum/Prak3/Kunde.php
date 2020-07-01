<?php // UTF-8 marker äöüÄÖÜß€
require_once "./Page.php";

class Kunde extends Page
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
    }

    protected function getViewData()
    {
        if (isset($_SESSION['order_id'])) {
            $orderID = $_SESSION['order_id'];

            $orderedArticles = array();

            $sql = <<<SQL
            SELECT ordered_articles.id, f_article_id, f_order_id, status, name 
            FROM ordered_articles 
                LEFT JOIN article ON f_article_id = article.id
            WHERE f_order_id = ?
            SQL;

            $stm = $this->_database->prepare($sql);
            $stm->bind_param('i', $orderID);
            $stm->execute();
            $result = $stm->get_result();
            if (!$result)
                throw new Exception("Fehler in Abfrage: " . $this->_database->error);
            while ($row = $result->fetch_assoc()) {
                $orderedArticles[] = $row;
            }
            $result->free();
            return $orderedArticles;
        }
        return [];
    }

    protected function generateView()
    {
        $orderedArticles = $this->getViewData();
        $this->generatePageHeader('Kunde');

        echo <<<EOT
        <header>
            <h1>Kunde (Lieferstatus)</h1>
        </header>
        EOT;

        foreach ($orderedArticles as $orderedArticle) {
            $status = intval($orderedArticle['status']);
            switch ($status) {
                case 0:
                    echo <<<EOT
                        <p>Bestellung {$orderedArticle["id"]}: Pizza {$orderedArticle["name"]}</p>
                        <p>Status: }</p>
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
            //Here
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}
// Here
Kunde::main();


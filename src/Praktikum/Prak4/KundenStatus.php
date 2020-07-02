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
        header("Content-Type: application/json; charset=UTF-8");
        $data = json_encode($orderedArticles);
        echo $data;
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


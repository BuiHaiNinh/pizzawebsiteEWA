<?php

require_once './BlockTemplate.php';

class SpeisekarteBlock extends BlockTemplate {
    protected function getViewData()
    {
        $articles = array();
        $sql = "SELECT * FROM article";
        $result = $this->_database->query($sql);
        if (!$result)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        $result->free();
        return $articles;
    }

    public function generateView($id = "")
    {
        $articles = $this->getViewData();
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo "<div $id>\n";

        echo "<h2>Speisekarte</h2>";

        foreach ($articles as $article) {
            echo "<div>";

            echo "<img src={$article['picture']} alt='' width='200' height='200'/>";
            echo "<p>{$article['name']}</p>";
            echo "<p>{$article['price']} €</p>";

            echo "</div>";
        }

        echo "</div>\n";
    }

    public function processReceivedData()
    {
    }
}
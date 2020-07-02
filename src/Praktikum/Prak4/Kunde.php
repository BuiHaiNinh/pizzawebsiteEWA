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

    }

    protected function generatePageHeader($headline = "")
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        echo <<<EOT
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <title>{$headline}</title>
            <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body onload="init()">
        EOT;

        // to do: output common beginning of HTML code
        // including the individual headline
    }

    protected function generateView()
    {
        $orderedArticles = $this->getViewData();
        $this->generatePageHeader('Kunde');
        echo <<<EOT
        <header>
            <h1 id="title">Kundeseite</h1>
        </header>
        EOT;

        echo "<script src='StatusUpdate.js'></script>";
        echo "<div id='bestellungen'></div>";

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


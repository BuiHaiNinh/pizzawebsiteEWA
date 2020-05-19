<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Bestellung extends Page
{

    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }


    protected function getViewData()
    {
    }

    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    public static function main()
    {
        try {
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Bestellung::main();

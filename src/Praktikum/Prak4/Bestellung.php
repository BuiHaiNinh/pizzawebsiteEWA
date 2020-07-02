<?php // UTF-8 marker äöüÄÖÜß€

require_once './Page.php';
require_once './SpeisekarteBlock.php';

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

    protected function processReceivedData()
    {
        parent::processReceivedData();
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        // Check variables
        if (isset($_POST['pizzasID']) && is_array($_POST['pizzasID'])) {
            $pizzasID = $_POST['pizzasID'];
            if (count($pizzasID) == 0) {
                return;
            }
        } else {
            return;
        }

        if (isset($_POST['pizzasUnits']) && is_array($_POST['pizzasUnits'])) {
            $pizzasUnit = $_POST['pizzasUnits'];
            if (count($pizzasUnit) == 0) {
                return;
            }
        } else {
            return;
        }

        if (isset($_POST['address']) && is_string($_POST['address'])) {
            $address = $_POST['address'];
        } else {
            return;
        }

        //var_dump($address);

        $this->_database->autocommit(false);
        // Begin transaction
        $query = <<<SQL
        INSERT INTO ordering (address, timestamp) 
        VALUE (?, NOW()) 
        SQL;

        $stmt = $this->_database->prepare($query);
        $stmt->bind_param('s', $address);

        $stmt->execute();
        // Get inserted ID
        $orderID = $this->_database->insert_id;

        for ($i = 0; $i < count($pizzasID); $i++) {
            $pizzaID = $pizzasID[$i];
            $unit = $pizzasUnit[$i];

            for ($n = 0; $n < $unit; $n++) {
                $query = <<<SQL
                    INSERT INTO ordered_articles (f_article_id, f_order_id, status) 
                    VALUE (?, ?, 0) 
                SQL;

                $stmt = $this->_database->prepare($query);
                $stmt->bind_param('si', $pizzaID, $orderID);
                $stmt->execute();
            }

        }

        $this->_database->commit();

        $_SESSION['order_id'] = $orderID;
        header('Location: http://localhost/Praktikum/Prak4/Bestellung.php');
    }

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

    protected function generateView()
    {
        $articles = $this->getViewData();
        $this->generatePageHeader('Bestellung');

        (new SpeisekarteBlock($this->_database))->generateView('speisekarte');

        $this->pizzaForm();

        $this->generatePageFooter();
    }


    public static function main()
    {
        try {
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }

    protected function pizzaForm(): void
    {
        echo "<form action='Bestellung.php' method='post'>";

        echo "<table id='cart-table'>";
        echo "<tr>";

        echo "<td>Name</td>";
        echo "<td>Unit</td>";
        echo "<td>Prices</td>";

        echo "</tr>";
        echo "</table>";

        echo "<p id='total-price'>0 €</p>";

        echo "<div id='hiding-input'></div>";

        echo <<<EOF
        <label>
            <input id="adress-input" onchange="check()" type="text" value="" name="address" placeholder="Ihre Adresse"/>
        </label>
        <input tabindex="1" onclick="clearAll()" type="reset" name="deleteAll" value="Alle Löschen"/>
        <input tabindex="2" type="button" name="delete" value="Löschen"/>
        <input id="submit-input" tabindex="3" type="button" onclick="redirectPage(this)" value="Bestellen" disabled/>
        EOF;

        echo "</form>";
    }
}

Bestellung::main();

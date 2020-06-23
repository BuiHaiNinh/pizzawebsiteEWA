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
        if (isset($_POST['warenkorb']) && is_array($_POST['warenkorb'])) {
            $warenkorb = $_POST['warenkorb'];
            if (count($warenkorb) == 0) {
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

        foreach ($warenkorb as $pizzaID) {
            $query = <<<SQL
            INSERT INTO ordered_articles (f_article_id, f_order_id, status) 
            VALUE (?, ?, 0) 
            SQL;

            $stmt = $this->_database->prepare($query);
            $stmt->bind_param('si', $pizzaID, $orderID);
            $stmt->execute();
        }

        $this->_database->commit();

        $_SESSION['order_id'] = $orderID;
        header('Location: http://localhost/Praktikum/Prak3/Bestellung.php');
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

        $this->pizzaSelection($articles);

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

    /**
     * @param array $articles
     */
    protected function pizzaSelection(array $articles): void
    {
        echo "<form action='Bestellung.php' method='post'>";
        echo "<label>";

        echo "<select tabindex='0' name='warenkorb[]' multiple size='5'>";

        $first = true;
        foreach ($articles as $article) {
            if ($first) {
                echo "<option selected value={$article['id']}>" . $article['name'] . "</option>";
                $first = false;
            } else {
                echo "<option  value={$article['id']}>" . $article['name'] . "</option>";
            }

        }

        echo "</select>";
        echo "</label>";

        echo <<<EOF
        <label>
            <input type="text" value="" name="address" placeholder="Ihre Adresse"/>
        </label>
        
        <!--        shoping cart-->
        <table class="shopping_cart">
          <tr> 
            <th>Name</th> 
            <th>Quantity</th> 
            <th>Sum</th> 
          </tr> 
          
          <tr> 
            <td>Row 1111
            <td>Row 2</td>
            <td>Row 3</td>
            <td>
                <input tabindex="1" type="button" name="delete" value="löschen">
                <input tabindex="2" type="button" name="delete_all" value="alles löschen">
            </td> 
          </tr> 
          
          <tr> 
            <td>Row 1111             
            <td>Row 2</td>
            <td>Row 3</td>
            <td>
                <input tabindex="1" type="button" name="delete" value="löschen">
                <input tabindex="2" type="button" name="delete_all" value="alles löschen">
            </td>  
          </tr>         
        </table>
        
        <br>      
          <span class="total">Total Price: </span>
        <br>
        
        <input tabindex="1" type="reset" name="deleteAll" value="Alle Löschen"/>
        <input tabindex="2" type="button" name="delete" value="Löschen"/>
        <input tabindex="3" type="submit" value="Bestellen"/>

        EOF;

        echo "</form>";
    }
}

Bestellung::main();

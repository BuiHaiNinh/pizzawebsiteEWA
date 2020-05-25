<?php
echo <<<EOF

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <title>FAHRER_SHIPPING_STATUS</title>
</head>

<body>
<section id="auslieferbare_Bestellungen">
    <h2>Fahrer Seite</h2>
    <form action="https://echo.fbi.h-da.de/" id="Fahrer_status_form" method="post" accept-charset="UTF-8">
        <p>Kunden Info: Name, Adresse</p>
        <p>Bestellung Infos: Bill, Pizzas</p>
        <p>Please select your status:</p>
        <input type="radio" id="ferig" name="Fahrer_status" value="ferig">
        <label for="ferig">Ferig</label><br>
        <input type="radio" id="unterwegs" name="Fahrer_status" value="unterwegs">
        <label for="unterwegs">Unterwegs</label><br>
        <input type="radio" id="geliefert" name="Fahrer_status" value="geliefert" checked>
        <label for="geliefert">Geliefert</label>
        <br>

        <input type="submit" value="Submit">
    </form>
</section>
</body>

</html>

EOF;

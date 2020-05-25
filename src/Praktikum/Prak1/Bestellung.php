<?php
echo <<< EOF

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellung</title>
</head>
<body>

<header>
    <h1>Bestellung</h1>
</header>

<section>
    <header>
        <h2>Speisekarte</h2>
    </header>

    <div>
        <img src="images/Margherita.jpg" alt="" width="200" height="200"/>
        <p>Margherita</p>
        <p>4.00 €</p>
    </div>

    <div>
        <img src="images/Salami.jpg" alt="" width="200" height="200"/>
        <p>Salami</p>
        <p>4.00 €</p>
    </div>

    <div>
        <img src="images/Hawaii.jpg" alt="" width="200" height="200"/>
        <p>Hawaii</p>
        <p>4.00 €</p>
    </div>

</section>

<section>
    <h2>Warenkorb</h2>
    <form action="https://echo.fbi.h-da.de/" method="post" id="bestellungForm">
        <label>
            <select tabindex="0" name="warenkorb[]" multiple size="5">
                <option>1. Hawait</option>
                <option selected>2. Hawait</option>
                <option>2. Salami</option>
            </select>
        </label>
        <p>12.0 €</p>

        <label>
            <input type="text" value="" name="adress" placeholder="Ihre Adresse"/>
        </label>
        <input type="reset" name="deleteAll" value="Alle Löschen"/>
        <input type="button" name="delete" value="Löschen"/>
        <input type="submit" value="Bestellen"/>
    </form>
</section>


</body>
</html>

EOF;

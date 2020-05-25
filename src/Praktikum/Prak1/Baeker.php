<?php
echo <<<EOF
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Baeker</title>
</head>
<body>

<header>
    <h1>Bestellung</h1>
</header>

<section>
    <h2>Bestellung 2: Pizza Hawaii</h2>


    <form action="https://echo.fbi.h-da.de/" method="post" id="baeckerForm" accept-charset="UTF-8">
        <div>
            Status:
            <label>
                <input type="radio" name="status" value=0 checked/>
                Bestellt
            </label>

            <label>
                <input type="radio" name="status" value=1/>
                Im Ofen
            </label>

            <label>
                <input type="radio" name="status" value=2/>
                Fertig
            </label>

        </div>
        <input type="submit" value="Ändern"/>
    </form>
</section>

</body>
</html>
EOF;

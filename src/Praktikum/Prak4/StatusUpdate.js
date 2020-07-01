const test = "[{\"id\":73,\"f_article_id\":1,\"f_order_id\":24,\"status\":0,\"name\":\"Salami\"},{\"id\":74,\"f_article_id\":2,\"f_order_id\":24,\"status\":0,\"name\":\"Vegetaria\"},{\"id\":75,\"f_article_id\":2,\"f_order_id\":24,\"status\":0,\"name\":\"Vegetaria\"},{\"id\":76,\"f_article_id\":3,\"f_order_id\":24,\"status\":0,\"name\":\"Spinat-H\u00fchnchen\"},{\"id\":77,\"f_article_id\":3,\"f_order_id\":24,\"status\":0,\"name\":\"Spinat-H\u00fchnchen\"},{\"id\":78,\"f_article_id\":3,\"f_order_id\":24,\"status\":0,\"name\":\"Spinat-H\u00fchnchen\"}]";
let json = JSON.parse(test);

function process(data) {
    "use strict";

    let bestellungenElement = window.document.getElementById('bestellungen');

    const statusStr = [
        'Bestellt',
        'Im Ofen',
        'Gebackt fertig',
        'Gebackt fertig. Warte zum liefern.',
        'Unterwegs',
        'Geliefert',
    ];

    while (bestellungenElement.hasChildNodes()) {
        bestellungenElement.removeChild(bestellungenElement.firstChild);
    }

    if (data.length === 0) {
        let emptyElement = window.document.createElement('p');
        emptyElement.textContent = 'Keine Bestellung';
        bestellungenElement.appendChild(emptyElement);
        return;
    }

    for (let bestellung of data) {
        let name = window.document.createElement('p');
        name.textContent = 'Bestellung ' + bestellung.id + ': Pizza ' + bestellung.name;

        let status = window.document.createElement('p');
        status.textContent = 'Status: ' + statusStr[bestellung.status];

        bestellungenElement.appendChild(name);
        bestellungenElement.appendChild(status);
    }
}

function init() {
    "use strict";
    requestData();
    window.setInterval(requestData, 2000);
}

let request = new XMLHttpRequest();

function requestData() {
    "use strict";
    request.open("GET", "KundenStatus.php");
    request.onreadystatechange = processData;
    request.send(null);
}

function processData() {
    "use strict";
    if (request.readyState === 4) { // Uebertragung = DONE
        if (request.status === 200) {   // HTTP-Status = OK
            if (request.responseText != null)
                process(JSON.parse(request.responseText));// Daten verarbeiten
            else console.error("Dokument ist leer");
        } else console.error("Uebertragung fehlgeschlagen");
    } else ;
}


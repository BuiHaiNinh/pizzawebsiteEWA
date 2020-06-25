function addToCart(element) {
    "use strict";

    // Extract data
    let id = element.parentNode.dataset.id;
    let name = element.parentNode.dataset.name;
    let price = parseFloat(element.parentNode.dataset.price);

    if (id == null || name == null || price == null)
        return;

    let tableElement = window.document.getElementById('cart-table');
    if (tableElement == null)
        return;

    for (let trElement of tableElement.childNodes) {
        if (trElement.dataset.id === id) {

            trElement.dataset.unit = parseInt(trElement.dataset.unit) + 1;
            trElement.firstChild.nextSibling.textContent = trElement.dataset.unit;

            trElement.dataset.price = parseFloat(trElement.dataset.price) + price;
            trElement.childNodes[2].textContent = parseFloat(trElement.dataset.price).toFixed(2) + '€';

            updateTotalPrice();
            updateForm();
            check();
            return;
        }
    }

    // Not found in cart
    let trElement = window.document.createElement('tr');
    trElement.dataset.id = id;
    trElement.dataset.name = name;
    trElement.dataset.price = price;
    trElement.dataset.originPrice = price;
    trElement.dataset.unit = 1;

    let nameElement = window.document.createElement('td');
    nameElement.textContent = name;

    let unitElement = window.document.createElement('td');
    unitElement.textContent = 1;

    let priceElement = window.document.createElement('td');
    priceElement.textContent = price.toFixed(2) + '€';

    let remove = window.document.createElement('input');
    remove.setAttribute('type', 'button');
    remove.setAttribute('value', 'Löschen');
    remove.setAttribute('onclick', 'deleteOneUnit(this)');

    let removeAllUnit = window.document.createElement('input');
    removeAllUnit.setAttribute('type', 'button');
    removeAllUnit.setAttribute('value', 'Alle Löschen');
    removeAllUnit.setAttribute('onclick', 'deleteAllUnit(this)');

    trElement.appendChild(nameElement);
    trElement.appendChild(unitElement);
    trElement.appendChild(priceElement);
    trElement.appendChild(remove);
    trElement.appendChild(removeAllUnit);

    tableElement.appendChild(trElement);

    updateTotalPrice();
    updateForm();
    check();
}

function deleteOneUnit(button) {
    "use strict";
    let unit = parseInt(button.parentElement.dataset.unit);
    unit--;
    if (unit === 0) {
        button.parentElement.parentElement.removeChild(button.parentElement)
    } else {
        button.parentElement.firstChild.nextSibling.textContent = unit;
        button.parentElement.dataset.unit = unit;

        let price = parseFloat(button.parentElement.dataset.price) - parseFloat(button.parentElement.dataset.originPrice);
        button.parentElement.dataset.price = price;
        button.parentElement.firstChild.nextSibling.nextSibling.textContent = price.toFixed(2) + '€';
    }

    updateTotalPrice();
    updateForm();
    check();
}

function deleteAllUnit(button) {
    "use strict";

    button.parentElement.parentElement.removeChild(button.parentElement);

    updateTotalPrice();
    updateForm();
    check();
}

function clearAll() {
    "use strict";

    let tableElement = window.document.getElementById('cart-table');

    let removed = [];
    for (let trElement of tableElement.childNodes) {
        if (trElement.dataset.id == null)
            continue;

        removed.push(trElement);
    }

    for (let tr of removed) {
        tableElement.removeChild(tr);
    }

    updateTotalPrice();
    updateForm();
    check();
}

function updateTotalPrice() {
    "use strict";
    let tableElement = window.document.getElementById('cart-table');
    let priceElement = window.document.getElementById('total-price');

    let total = 0;
    for (let trElement of tableElement.childNodes) {
        if (trElement.dataset.id == null)
            continue;
        total += parseFloat(trElement.dataset.price);
    }

    priceElement.textContent = total.toFixed(2) + '€';
}

function updateForm() {
    "use strict";
    let hidingsInput = window.document.getElementById('hiding-input');
    let tableElement = window.document.getElementById('cart-table');

    while (hidingsInput.hasChildNodes()) {
        hidingsInput.removeChild(hidingsInput.firstChild);
    }

    for (let trElement of tableElement.childNodes) {
        if (trElement.dataset.id == null)
            continue;

        let hidingID = document.createElement('input');
        hidingID.setAttribute('type', 'hidden');
        hidingID.setAttribute('name', 'pizzasID[]');
        hidingID.setAttribute('value', trElement.dataset.id);

        let hidingUnits = document.createElement('input');
        hidingUnits.setAttribute('type', 'hidden');
        hidingUnits.setAttribute('name', 'pizzasUnits[]');
        hidingUnits.setAttribute('value', trElement.dataset.unit);

        hidingsInput.appendChild(hidingID);
        hidingsInput.appendChild(hidingUnits)
    }
}

function check() {
    "use strict";
    let addressInput = window.document.getElementById('adress-input');
    let tableElement = window.document.getElementById('cart-table');
    let submitButton = window.document.getElementById('submit-input');

    let active = true;
    if (addressInput.value.length === 0)
        active = false;

    if (tableElement.childElementCount <= 1)
        active = false;

    submitButton.disabled = !active

}
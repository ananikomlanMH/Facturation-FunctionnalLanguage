function addToForm(id, productName, unitPrice) {
    // Récupérer le formulaire
    var form = document.getElementById('Form');

    // Vérifier si le produit est déjà dans le formulaire
    var existingRow = document.querySelector('#productTable tbody tr[data-product-id="' + id + '"]');

    if (existingRow) {
        // Si le produit existe déjà, incrémenter la quantité
        var quantityInput = existingRow.querySelector('input[name="qte[]"]');
        var currentQuantity = parseInt(quantityInput.value) || 0;
        quantityInput.value = currentQuantity + 1;
    } else {
        // Sinon, ajouter une nouvelle ligne dans le tableau du formulaire
        var newRow = form.querySelector('#productTable tbody').insertRow();
        newRow.setAttribute('data-product-id', id);
        newRow.setAttribute('data-product-name', productName);

        // Ajouter des cellules à la nouvelle ligne
        var cellId = newRow.insertCell(0);
        var cellProduct = newRow.insertCell(1);
        var cellQuantity = newRow.insertCell(2);
        var cellPrice = newRow.insertCell(3);
        var cellAction = newRow.insertCell(4);

        // Remplir les cellules avec les données du produit
        cellId.innerHTML = id;
        cellProduct.innerHTML = productName;
        cellQuantity.innerHTML = `
        <input type="hidden" name="ids[]" value="${id}">
        <input type="number" name="qte[]" class="form-control" value="1">`;
        cellPrice.innerHTML = unitPrice;
        cellAction.innerHTML = '<span class="" onclick="removeRow(this)"><i class="fas fa-times" id="close">X</i></span>';
    }
}


function removeRow(span) {
    // Supprimer la ligne du tableau du formulaire
    var row = span.closest('tr');
    row.remove();
}

function validateForm() {
    var productRows = document.querySelectorAll('#productTable tbody tr');

    if (productRows.length === 0) {
        notifyAlert('La table des produits ne peut pas être vide. Ajoutez au moins un produit.');
        return false;
    }

    for (var i = 0; i < productRows.length; i++) {
        var productId = productRows[i].getAttribute('data-product-id');
        var productName = productRows[i].getAttribute('data-product-name');
        var quantityInput = productRows[i].querySelector('input[name="qte[]"]');
        var quantityValue = parseInt(quantityInput.value);

        if (quantityValue <= 0) {
            notifyAlert('La quantité du produit : ' + productName + ' doit être supérieure à zéro.');
            return false;
        }
    }

    // Si tout est correct, le formulaire peut être soumis
    return true;
}

function resetTable() {
    // Récupérer la table des lignes de commande
    var table = document.getElementById('productTable');

    // Vider le corps de la table
    var tbody = table.querySelector('tbody');
    tbody.innerHTML = '';
}

function notifyAlert(message) {
    $.confirm({
        icon: 'fa fa-info',
        title: 'Alerte',
        content: message,
        useBootstrap: false,
        boxWidth: '500px',
        // autoClose: 'Annuler|10000',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        buttons: {
            ok: function () {
            }
        }
    });
}
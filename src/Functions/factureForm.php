<?php

function _getSelectClient(): string{

    $clients = _getDataFromDb('clients');
    $list = '
    <label for="client">Client</label>
    <select name=client id=client class=form-control>';
    foreach($clients as $client){
        $list .="<option value=".$client['id'].">".$client['raison']."</option>";
    }
    $list .= '</select>';
    return $list;
}

function _getProduitList(){
    $produits = _getDataFromDb('produits');

    $table = '
    <table class="table table-hover" id="availableProducts">
        <thead>
          <tr>
            <th>Produit</th>
            <th>Prix unitaire</th>
          </tr>
        </thead>
        <tbody>
    ';
    foreach($produits as $p){
        $table .= '
        <tr data-product-id='.$p['id'].' data-product-name='.$p['libelle'].' data-product-price='.$p['pu'].' onclick="addToForm('.$p['id'].',\''.$p['libelle'].'\','.$p['pu'].')">
          <td>'.$p['libelle'].'</td>
          <td>'.$p['pu'].'</td>
        </tr>
      ';
    }

    $table .= '</tbody>
    </table>';

    return $table;

}

$form = '<div class="row">
    <h3>Nouvelle facture</h3>
    
    <div class="col-md-8">
      <form id="Form" method="post" onsubmit="return validateForm()">
        <div class="form-group">
        '._getSelectClient().'
        </div>
        <div class="form-group">
          <label for="productTable">Produits :</label>
          <table class="table" id="productTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>&nbsp;
        <button type="reset" class="btn btn-danger"  onclick="resetTable()">Réinitialiser</button>
      </form>
    </div>

    <div class="col-md-4">
      '._getProduitList().'
    </div>

  </div>';

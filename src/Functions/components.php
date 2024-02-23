<?php

use Dompdf\Dompdf;

function _getTable(array $data, array $head, $table_actions, string $class = ''): string
{

    $th = '';
    foreach ($head as $key => $item) {
        $th .= <<<HTML
            <th>$item</th>
        HTML;

    }

    $body = '';

    foreach ($data as $item) {
        $body .= '<tr>';
        foreach ($head as $key => $_item) {
            $show = $item[$key];

            if (is_int($show) || is_float($show)) {
                $show = number_format($show, 0, '.', ' ');
            }

            $body .= <<<HTML
             <td>$show</td>
            HTML;
        }

        if (!empty($table_actions)) {
            $body .= <<<HTML
            <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu" style="">
            HTML;
            foreach ($table_actions as $_key_actions => $action) {
                $id = $item['id'];
                $body .= <<<HTML
                    <a class="dropdown-item" href="?page=$_key_actions&id=$id">
                    <i class="ti ti-point me-1"></i> $action</a>
                HTML;

            }
            $body .= <<<HTML
            </div>
                </div>
            </td>
            HTML;
        }

        $body .= '</tr>';
    }

    return <<<HTML
        <div class="text-nowrap">
            <table class="table">
                <thead>
                <tr>
                   $th
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                $body
                </tbody>
            </table>
        </div>
        HTML;
}

function _getView(string $table, array $fields, array $table_actions = []): string
{
    $title = ucfirst($table);

    $add_link = sprintf('?page=add_%s', $table);
    $table = _getTable(_getDataFromDb(
        $table, $fields
    ), $fields, $table_actions);
    return <<<HTML
         <div class="d-flex justify-content-between">
                <h3 class="mb-9">$title</h3>
                <p>
                    <a href="$add_link" class="btn btn-primary">Nouveau <i class="ti ti-plus"></i></a>
                </p>
            </div>
        $table
HTML;


}

function _getViewForm(string $table, array $form, string $action): string
{

    $form = implode("", $form);
    _addToDb($table);
    _editToDb($table);

    $title = match ($action) {
        'add_to_db' => "Nouveau",
        'edit_to_db' => "Edition",
        default => '',
    };
    return <<<HTML
<div class="card mt-9">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">$title $table</h5>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" class="needs-validation" novalidate="">
                        $form
                        <input type="hidden" name="$action" value="$table">
                        <button type="submit" class="btn btn-primary">Enregister</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
HTML;

}

function _getFactureForm(): string
{
    $clients_select = _select('Clients', 'client_id', _getDataFromDb('clients'), 'id', 'raison');

    $produits = _getProduitList();

    _addFactureToDb('factures');
    return <<<HTML
<div class="row">
    <h3>Nouvelle facture</h3>
    
    <div class="col-md-8">
      <form id="Form" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="facture" value="facture">
        <div class="form-group">
        $clients_select
        </div>
        <div class="form-group">
<!--          <label for="productTable">Produits :</label>-->
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
    $produits
    </div>

  </div>
HTML;

}

function _getProduitList(): string
{
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
    foreach ($produits as $p) {
        $table .= '
        <tr data-product-id=' . $p['id'] . ' data-product-name=' . $p['libelle'] . ' data-product-price=' . $p['pu'] . ' onclick="addToForm(' . $p['id'] . ',\'' . $p['libelle'] . '\',' . $p['pu'] . ')">
          <td>' . $p['libelle'] . '</td>
          <td>' . $p['pu'] . '</td>
        </tr>
      ';
    }

    $table .= '</tbody>
    </table>';

    return $table;

}

function _getFacturePrint($id): void
{
    $facture = _findDataFromDb('factures', $id);
    $db = new \App\DB\DBConnect();

    $ligneFacture = $db->query("SELECT p.libelle as libelle, lf.qte, lf.pu, (lf.qte*lf.pu) as m_ht, (lf.qte*lf.pu)*1.19 as m_ttc, (lf.qte*lf.pu)*0.19 as tva FROM lignes_factures as lf, produits as p WHERE lf.factures_id = ".$facture['id']);

    $client = _findDataFromDb('clients', $facture['clients_id']);

    $total_general = array_sum(array_map(fn($item) => $item['m_ttc'], $ligneFacture));
    $dompdf = new Dompdf();

    ob_start();
        require 'facturePrint.php';
    $content = ob_get_clean();
    $options = $dompdf->getOptions();
//    $options->setDefaultFont('Arial');
    $dompdf->setOptions($options);
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream(sprintf("Facture N°%s.pdf", $facture['code']), array("Attachment" => false));
    exit(0);
}
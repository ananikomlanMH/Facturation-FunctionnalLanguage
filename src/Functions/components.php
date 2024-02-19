<?php
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
                    <a class="dropdown-item" href="?page=$_key_actions&id=$id"><i class="ti ti-edit me-1"></i> $action</a>
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
<?php
function _addToDb(string $table): void
{
    if (!empty($_POST['add_to_db'])) {
        unset($_POST['add_to_db']);
        $db = new \App\DB\DBConnect();

        $fields = implode(",", array_keys($_POST));
        $values = '';
        foreach (array_keys($_POST) as $item) {
            $values .= ':' . $item . ",";
        }
        $values = rtrim($values, ',');

        $db->queryBuild("INSERT INTO $table ($fields) VALUE ($values)", $_POST);
        header(sprintf('Location: /?page=%s&notify_message=%s&type=%s', $table, 'Ajout effectué avec succés', 'success'));
    }
}
function _editToDb(string $table): void
{
    if (!empty($_POST['edit_to_db'])) {
        unset($_POST['edit_to_db']);
        $db = new \App\DB\DBConnect();

        $id = $_POST['id'];
        unset($_POST['id']);
        $sets = '';
        foreach (array_keys($_POST) as $item) {
            $sets .= sprintf('%s = :%s,', $item, $item);
        }
        $sets = rtrim($sets, ',');


        $db->queryBuild("UPDATE $table SET $sets where id = $id", $_POST);
        header(sprintf('Location: /?page=%s&notify_message=%s&type=%s', $table, 'Edition effectué avec succés', 'success'));
    }
}

function _getDataFromDb(string $table, array $fields = []): array
{
    $db = new \App\DB\DBConnect();

    $select = !empty($fields) ? implode(',', array_keys($fields)) : '*';

    $req = "SELECT $select FROM $table";

    return $db->query($req);
}

function _findDataFromDb(string $table, int $id): array|null
{
    $db = new \App\DB\DBConnect();

    $req = "SELECT * FROM $table where id = $id";

    return $db->query($req)[0] ?? null;
}
function _deleteDataFromDb(string $table, int $id): void
{
    $db = new \App\DB\DBConnect();

    $req = "DELETE FROM $table where id = $id";

    if ($db->queryBuild($req)){
        header(sprintf('Location: /?page=%s&notify_message=%s&type=%s', $table, 'Suppression effectué avec succés', 'success'));

    }else{
        header(sprintf('Location: /?page=%s&notify_message=%s&type=%s', $table, 'Erreur lors de la suppressions', 'danger'));
    }

}
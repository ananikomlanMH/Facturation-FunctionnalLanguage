<?php
require dirname(__DIR__, 1) . '/Models/model.php';
require dirname(__DIR__, 1) . '/Functions/forms.php';
require dirname(__DIR__, 1) . '/Functions/components.php';
function _getBodyContent(): string
{

    $page = $_REQUEST['page'] ?? null;

    switch ($page) {
        case 'clients':
            $content = _getView('clients', [
                'id' => '#',
                'raison' => 'Raison',
                'tel' => 'Téléphone',
                'adresse' => 'Adresse',
                'email' => 'Email'
            ],
            [
                'edit_clients' => "Editer",
                'delete_clients' => "Supprimer",
            ]
            );
            break;
        case 'add_clients':
            $content = _getViewForm('clients', [
                _input('raison', 'Raison sociale', 'text', null),
                _input('tel', 'Téléphone', 'text', null),
                _input('adresse', 'Adresse', 'text', null),
                _input('email', 'Email', 'text', null),
            ], 'add_to_db');
            break;
        case 'edit_clients':
            $_data = _findDataFromDb('clients', $_GET['id']);
            if (!$_data){
                $content = _404();
                break;
            }
            $content = _getViewForm('clients', [
                _input('raison', 'Raison sociale', 'text', $_data['raison']),
                _input('tel', 'Téléphone', 'text', $_data['tel']),
                _input('adresse', 'Adresse', 'text', $_data['adresse']),
                _input('email', 'Email', 'text', $_data['email']),
                _input('id', 'id', 'hidden', $_data['id']),
            ], 'edit_to_db');
            break;
        case 'delete_clients':
            $_data = _findDataFromDb('clients', $_GET['id']);
            if (!$_data){
                $content = _404();
                break;
            }
            _deleteDataFromDb('clients', $_data['id']);
            break;
        case 'produits':
            $content = _getView('produits', [
                'id' => '#',
                'libelle' => 'Libellé',
                'pu' => 'Prix Unitaire',
                'pa' => "Prix d'achat",
            ]);
            break;

        default:
            $content = _404();
            break;
    }

    return $content;
}

function _404(): string
{
    return <<<HTML
<div style="display:flex;justify-content: center">
<img src="/assets/images/backgrounds/404.svg" alt="" srcset="" style="height: 80vh;">
</div>

HTML;
}
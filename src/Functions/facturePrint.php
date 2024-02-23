<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
    <style>
        body{
            font-family: 'sans-serif', serif;
        }
        table{
            border-collapse: collapse;
        }

        #table {
            font-size: .8em;
            border-collapse: collapse;
            width: 100%;
        }

        #table td, #table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table tr:hover {background-color: #ddd;}

        #table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #EF8F07;
            color: white;
        }

    </style>
</head>
<body>
<img src="./assets/images/head.png" alt="">
<h1 style="text-decoration:underline;text-align:center;">Facture N° <?= $facture['code'] ?></h1>

<table id="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Article</th>
        <th>Pu</th>
        <th>Qte</th>
        <th>Total HT</th>
        <th>TVA</th>
        <th>Total TTC</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($ligneFacture as $key => $item): ?>
    <tr>
        <td><?= $key +1 ?></td>
        <td><?= $item['libelle'] ?></td>
        <td><?= number_format($item['pu'], 0, '.', ' ') ?></td>
        <td><?= number_format($item['qte'], 0, '.', ' ') ?></td>
        <td><?= number_format($item['m_ht'], 0, '.', ' ') ?></td>
        <td><?= number_format($item['tva'], 0, '.', ' ') ?></td>
        <td><?= number_format($item['m_ttc'], 0, '.', ' ') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="6" style="text-align:left;">Total</th>
        <th><?= number_format($total_general, 0, '.', ' ') ?></th>
    </tr>
    </tbody>
</table>
</body>
</html>
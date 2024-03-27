<?php

include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$nbPerPage = 50;
$page = 1;
if (isset($_GET['p']) && $_GET['p'] > 0) {
    $page = $_GET['p'];
}

$sql = "SELECT COUNT(*) AS total FROM table_product";
$stmt = $db->prepare($sql);
$stmt->execute();
$row = $stmt->fetch();
$total = $row['total'];

$sql = "SELECT * FROM table_product LEFT JOIN table_type ON table_product.product_type_id = table_type.type_id ORDER BY product_name LIMIT :offset, :limit";
$stmt = $db->prepare($sql);
$stmt->bindValue(':offset', ($page - 1) * $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->execute();
$recordset = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/product/css/style.css">
    <script src="/js/delete-popup.js" defer></script>

</head>

<body>
<table class="table table-dark table-striped table-bordered">
    <thead>
    <tr>
        <th scope="col">couverture</th>
        <th scope="col">série</th>
        <th scope="col">title</th>
        <th scope="col">prix</th>
        <th scope="col">type</th>
        <th scope="col">action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($recordset as $row) { ?>
        <tr>
            <td>
                <?php if ($row['product_image'] != "") { ?>
                    <img src="/upload/product/lg_<?= htmlspecialchars($row['product_image']); ?>"
                         alt="Couverture de la BD : <?= $row['product_name']; ?>" width="150"/>
                <?php } ?>
            </td>
            <td> <?= htmlspecialchars($row['product_serie']); ?> </td>
            <td> <?= htmlspecialchars($row['product_name']); ?> </td>
            <td> <?= htmlspecialchars($row['product_price']); ?> </td>
            <td> <?= htmlspecialchars($row['type_name']) ?></td>
            <td><a href="form.php?id= <?= htmlspecialchars($row['product_id']); ?>">modifier</a></td>
            <td>
                <button class="btn_delete btn btn-warning" data-id="<?= htmlspecialchars($row['product_id']); ?>">
                    supprimer
                </button>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<ul>
    <?php for ($i = 1; $i <= ceil($total / $nbPerPage); $i++) { ?>
        <li><a href="index.php?p=<?= $i; ?>"><?= $i; ?></a></li>
    <?php } ?>
</ul>
<span style="display:none" id="token"><?= $_SESSION['token']; ?></span>
<dialog id="modal-delete">
    <p>Es-tu sûr de toi mon frérot ?</p>
    <button class="btn btn-success" id="modal-cancel">cancel</button>
    <a class="btn btn-warning" id="modal-confirm">confirm</a>
</dialog>

</body>

</html>
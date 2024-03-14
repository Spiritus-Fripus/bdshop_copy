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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <table>
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
                            <img src="/upload/product/lg_<?= htmlspecialchars($row['product_image']); ?>" alt="Couverture de la BD : <?= $row['product_name']; ?>" width="150" />
                        <?php } ?>
                    </td>
                    <td> <?= htmlspecialchars($row['product_serie']); ?> </td>
                    <td> <?= htmlspecialchars($row['product_name']); ?> </td>
                    <td> <?= htmlspecialchars($row['product_price']); ?> </td>
                    <td> <?= htmlspecialchars($row['type_name']) ?></td>
                    <td><a href="form.php?id=<?= htmlspecialchars($row['product_id']); ?>">modifier</a></td>
                    <td><a href="delete.php?id=<?= htmlspecialchars($row['product_id']); ?>">supprimer</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <ul>
        <?php for ($i = 1; $i <= ceil($total / $nbPerPage); $i++) { ?>
            <li><a href="index.php?p=<?= $i; ?>"><?= $i; ?></a></li>
        <?php } ?>
    </ul>
</body>

</html>
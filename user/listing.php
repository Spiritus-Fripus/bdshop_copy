<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

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
    <title>Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/user/css/style-user.css">
</head>

<body>
    <header>
        <H1>BD SHOP</H1>
    </header>
    <div id="divCart"></div>
    <main>
        <?php foreach ($recordset as $row) { ?>
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <p class="title"><?= $row['product_name'] ?></p>
                        <p><?= $row['product_price'] ?>€</p>
                    </div>
                    <div class="flip-card-back">
                        <?php if ($row['product_image'] != "") { ?>
                            <img src="/upload/product/lg_<?= htmlspecialchars($row['product_image']); ?>" alt="Couverture de la BD : <?= $row['product_name']; ?>" width="150" />
                        <?php } ?>
                        <button class="add_cart" data-id="<?= $row['product_id']; ?>">Add to cart</button>
                    </div>
                </div>
            </div>
        <?php }; ?>
    </main>
    <ul>
        <?php for ($i = 1; $i <= ceil($total / $nbPerPage); $i++) { ?>
            <li><a href="listing.php?p=<?= $i; ?>"><?= $i; ?></a></li>
        <?php } ?>
    </ul>

</body>
<script src="/js/fetch-cart.js"></script>

</html>
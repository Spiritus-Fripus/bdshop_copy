<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$product_name = "";
$product_serie = "";
$product_id = 0;
$product_price = 0;
$product_type_id = 0;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_product WHERE product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_id", $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $product_name = $row['product_name'];
        $product_serie = $row['product_serie'];
        $product_id = $row['product_id'];
        $product_price = $row['product_price'];
        $product_type_id = $row['product_type_id'];
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="process.php" method="POST" enctype="multipart/form-data">

        <label for="product_name">Titre</label>
        <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product_name); ?>">

        <label for="product_serie">Serie</label>
        <input type="text" id="product_serie" name="product_serie" value="<?= htmlspecialchars($product_serie); ?>">

        <label for="product_price">Prix</label>
        <input type="text" id="product_price" name="product_price" value="<?= htmlspecialchars($product_price); ?>">

        <label for="product_type_id">Type</label>
        <select name="product_type_id" id="product_type_id" required>
            <option value="">Sélectionnez ...</option>
            <!-- liste déroulante auto-->
            <?php
            $sqlType = "SELECT * FROM table_type";
            $stmtType = $db->prepare($sqlType);
            $stmtType->execute();
            $recordsetType = $stmtType->fetchAll();
            foreach ($recordsetType as $rowType) { ?>
                <option value="<?= htmlspecialchars($rowType['type_id']); ?>" <?= $rowType['type_id'] == $product_type_id ? "selected" : ""; ?>><?= htmlspecialchars($rowType['type_name']); ?></option>
            <?php } ?>
            <!--------------------------->

            <label for="product_image">Image</label>
            <input type="file" name="product_image" id="product_image">

            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">

            <input type="submit" value="Enregistrer">

    </form>
</body>

</html>
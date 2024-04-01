<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";
include $_SERVER['DOCUMENT_ROOT'] . "/class/Product.class.php";

$sql = "SELECT * FROM table_product ";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($recordset as $row) {
    $product = new Product($row);
    echo $product->getName() . "<br/>";
    var_dump($product);
}

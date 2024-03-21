<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_product WHERE product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_id", $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $recordset = $stmt->fetch(PDO::FETCH_ASSOC);
    print json_encode($recordset, JSON_PRETTY_PRINT);
}

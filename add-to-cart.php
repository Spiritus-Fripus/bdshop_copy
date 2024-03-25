<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_cart WHERE cart_product_id = :cart_product_id AND cart_customer_id = :cart_customer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":cart_product_id", $_GET['id']);
    $stmt->bindValue(":cart_customer_id", 42); // a remplacer par la variable de session
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $quantity = $row['cart_quantity'];
        $quantity += 1;
        $sql = "UPDATE table_cart SET product_quantity = $quantity WHERE cart_id = :cart_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":cart_id", $row['cart_id']);
        $stmt->execute();
    } else {
        $quantity = $row['cart_quantity'];
        $quantity = 1;
        $sql = "INSERT INTO table_cart (cart_product_id,cart_customer_id,cart_quantity) 
                VALUES (:cart_product_id,:cart_customer_id,:cart_quantity)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":cart_product_id", $row['cart_product_id']);
        $stmt->bindValue(":cart_customer_id", 42);
        $stmt->bindValue(":cart_quantity", $quantity);
        $stmt->execute();
    } 
    $sql = "SELECT *"
}

// session_start();
// $_SESSION['customer_id'] = 42;
// $recordset = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print json_encode($recordset, JSON_PRETTY_PRINT);
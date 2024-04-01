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
        $quantity -= 1;

        if ($quantity <= 0) {
            $sql = "DELETE FROM table_cart WHERE cart_product_id = :cart_product_id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":cart_product_id", $_GET['id']);
            $stmt->execute();
        } else {
            $sql = "UPDATE table_cart SET cart_quantity = :quantity WHERE cart_product_id = :cart_product_id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":quantity", $quantity);
            $stmt->bindValue(":cart_product_id", $_GET['id']);
            $stmt->execute();
        }
    }
}

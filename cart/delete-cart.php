<?php

include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


if (isset($_POST['id']) && $_POST['id'] > 0) {
    $sql = "SELECT * FROM table_cart WHERE  cart_customer_id = :cart_customer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":cart_customer_id", 42); // a remplacer par la variable de session
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $sql = "DELETE FROM table_cart WHERE cart_customer_id= :cart_customer_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([":cart_customer_id" => 42]);
    }
}
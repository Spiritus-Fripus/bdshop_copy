<?php

include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_GET['id']) && $_GET['id']) {
	$sql = "DELETE FROM table_product WHERE product_id = :product_id";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(":product_id", $_GET['id'], PDO::PARAM_INT);
	$stmt->execute();
}

header("Location: index.php");
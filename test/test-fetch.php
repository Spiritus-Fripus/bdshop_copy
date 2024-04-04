<?php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


$sql = "SELECT * FROM table_customer";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll(PDO::FETCH_ASSOC);
print json_encode($recordset, JSON_PRETTY_PRINT);
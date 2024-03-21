<?php
include $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$total = 0;

if (!empty($_POST['mail'])) {

    $sql = "SELECT COUNT(*) AS total FROM table_customer WHERE customer_mail = :customer_mail";
    $stmt = $db->prepare($sql);
    $stmt->execute([":customer_mail" => $_POST['mail']]);
    if ($row = $stmt->fetch()) {

        $total = $row['total'];
    };
};

echo $total;

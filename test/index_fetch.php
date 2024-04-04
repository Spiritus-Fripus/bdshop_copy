<?php

include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


$sql = "SELECT * FROM table_customer ";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>test</h1>
<?php foreach ($recordset as $row) { ?>
    <div id="div_test" data-id="<?= $row['customer_id']; ?>"></div>
<?php } ?>
</body>
<script src="test-fetch-louis.js" defer></script>
</html>

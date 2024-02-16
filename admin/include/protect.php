<?php
session_start();
if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] != "ok") {
    header("Location: /admin/include/login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>LOGED</h1>
</body>

</html>
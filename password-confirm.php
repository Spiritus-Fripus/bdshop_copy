<?php

include $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

if (isset($_GET['token'])) {
    $sql = "SELECT * FROM table_customer WHERE customer_token = :customer_token";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":customer_token", $_GET['token']);
    $stmt->execute();
    $row = $stmt->fetch();
}
if (isset($_POST['password']) && $_POST['password'] == $_POST['password-confirm']) {
    $sql = "UPDATE table_customer SET customer_password = :customer_password WHERE customer_id = :customer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":customer_password", password_hash($_POST['password-confirm'], PASSWORD_DEFAULT));
    $stmt->bindValue(":customer_id", $_POST['customer_id']);
    $stmt->execute();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php if (isset($_GET['token'])) { ?>
        <h2>Change your password</h2>
        <form action="password-confirm.php" method="post">
            <label for="password">Password
                <input type="password" name="password">
            </label>
            <label for="password_confirm">Password-confirm
                <input type="password" name="password-confirm">
            </label>
            <input type="submit">
            <input type="hidden" name="customer_id" value="<?= $row['customer_id']; ?>">
        </form>
    <?php } else { ?>
        <h2>Your password has been changed</h2>
    <?php } ?>
</body>

</html>
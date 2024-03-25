<?php

include $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$step = 1;
if (!empty($_POST['mail_recover'])) {
    $step = 2;
    $sql = "SELECT customer_id FROM table_customer WHERE customer_mail = :customer_mail AND customer_status = 1 ORDER BY customer_id DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute([":customer_mail" => $_POST['mail_recover']]);
    if ($row = $stmt->fetch()) {
        $token = md5(random_int(0, 100000)) . date("ymdhis");
        $customer_id = $row['customer_id'];
        $sql = "UPDATE table_customer SET customer_token = :customer_token WHERE customer_id = :customer_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":customer_token", $token);
        $stmt->bindValue(":customer_id", $customer_id);
        $stmt->execute();
        $link = "<a href='password.php?id=" . $customer_id . "&token=" . $token . "'>Reset your password</a>";
    };
};

if (!empty($_GET['id']) && !empty($_GET['token'])) {
    $sql = "SELECT customer_id FROM table_customer WHERE customer_id = :customer_id AND customer_token = :customer_token";
    $stmt = $db->prepare($sql);
    $stmt->execute([':customer_id' => $_GET['id'], ':customer_token' => $_GET['token']]);
    if ($row = $stmt->fetch()) {
        $step = 3;
        $customer_id = $row['customer_id'];
    }
};
if (!empty($_POST['pwd_change']) && !empty($_POST['pwd_change_confirm']) && $_POST['pwd_change'] == $_POST['pwd_change_confirm']) {
    $step = 4;
    $sql = "UPDATE table_customer SET customer_token='', customer_password = :customer_password WHERE customer_id = :customer_id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':customer_password' => password_hash($_POST['pwd_change'], PASSWORD_DEFAULT), ':customer_id' => $_POST['customer_id']]);
};
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
    <h1>Password forgotten ?</h1>
    <?php if ($step == 1) { ?>
        <form action="" method="post">
            <label for="mail_recover"> Your mail :
                <input type="email" name="mail_recover">
                <input type="submit" value="Envoyer">
            </label>
        </form>
    <?php } ?>
    <?php if ($step == 2) { ?>
        <p>A mail has been sent to your personnal adress</p>
        <?= $link; ?>
    <?php } ?>
    <?php if ($step == 3) { ?>
        <form action="" method="post">
            <label for="pwd_change">
                <input type="password" name="pwd_change">
            </label>
            <label for="pwd_change_confirm">
                <input type="password" name="pwd_change_confirm">
            </label>
            <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
            <input type="submit" value="Envoyer">
        </form>
    <?php } ?>
    <?php if ($step == 4) { ?>
        <p>Your password has been updated</p>
        <a href="/admin/include/login.php">login</a>
    <?php } ?>
</body>

</html>
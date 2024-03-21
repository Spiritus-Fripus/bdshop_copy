<?php
include $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$try = 0;
if (!empty($_POST['mail_recover'])) {
    $sql = "SELECT * FROM table_customer WHERE customer_mail = :customer_mail";
    $stmt = $db->prepare($sql);
    $stmt->execute([":customer_mail" => $_POST['mail_recover']]);
    if ($row = $stmt->fetch()) {
        $try = 1;
    };
    if ($try == 1) {
        $sql = "UPDATE table_customer SET customer_token = :customer_token WHERE customer_id = :customer_id";
        $token = md5(random_int(0, 100000)) . date("ymdhis");
        $id = $row['customer_id'];
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":customer_token", $token);
        $stmt->bindValue(":customer_id", $id);
        $stmt->execute();
    }
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
    <form action="mail-reset.php" method="post">
        <?php if ($try == 0) { ?>
            <label for="mail_recover"> Your mail :
                <input type="email" name="mail_recover">
                <input type="hidden" name="mail_sent">
            </label>
        <?php } else { ?>
            <h2>Click here to reset your password</h2>
            <a href="password-confirm.php?token=<?= htmlspecialchars($token) ?>"> Reset mail token: (<?= $token ?>) </a>
        <?php } ?>
    </form>
</body>

</html>
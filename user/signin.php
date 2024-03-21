<?php
// echo $_SERVER['HTTP_REFERER'];
// nous sert à voir de quelle page on vient
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// on vérifie que le formulaire a bien été  envoyé avant de traiter
if (isset($_POST['subscription_sent'])) {
    $sql = "INSERT INTO table_customer (customer_lastname, customer_mail, customer_password, customer_token) VALUES (:customer_lastname, :customer_mail, :customer_password, :customer_token)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":customer_lastname", $_POST['customer_lastname']);
    $stmt->bindValue(":customer_mail", $_POST['customer_mail']);
    $stmt->bindValue(":customer_password", password_hash($_POST['customer_password'], PASSWORD_DEFAULT));
    $token = md5(random_int(0, 100000)) . date("ymdhis");
    $stmt->bindValue(":customer_token", $token);
    $stmt->execute();
    $id = $db->lastInsertId();
    //idéalement on envoie un mail à ce moment là, on va donc le simuler
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
    <?php
    if (!isset($_POST['subscription_sent'])) { ?>
        <form action="signin.php" method="post">
            <label for="name">Nom (*)</label>
            <input type="text" name="customer_lastname" id="name" required>
            <label for="mail">Mail (*)</label>
            <input type="email" name="customer_mail" id="mail" required>
            <label for="password">Mot de passe (*)</label>
            <input type="password" name="customer_password" id="pwd" required>
            <input type="hidden" name="subscription_sent" value="1">
            <input class="bouton" type="submit" value="Enregistrer">
        </form>
    <?php
    } else { ?>
        <div>
            <p> Un mail de confirmation vous a été envoyé, merci de vérifier vos spams et de cliquer sur le lien de confirmation</p>
            <a href="signin_confirm.php?id=<?= $id; ?>&token=<?= $token; ?>">Cliquez ici</a>
        </div>
    <?php } ?>
</body>

<script src="/js/verify-user.js"></script>

</html>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_GET['id']) && !empty($_GET['token'])) {
    $sql = "SELECT * FROM table_customer WHERE customer_id = :id AND customer_token = :token";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $_GET['id'], ":token" => $_GET['token']]);
    if ($row = $stmt->fetch()) {
        $sql = "UPDATE table_customer SET customer_status=1,customer_token ='',customer_subscription_date=:date WHERE customer_id=:id";
        $stmt = $db->prepare($sql);
        $stmt->execute([":id" => $_GET['id'], ":date" => date("Y-m-d")]);
    } else { ?>
        <div>
            <p>Oups, vous vous êtes trompés quelque part...</p>
        </div>
        /* problème : pas trouvé id/token, donc soit hack soit erreur. */
    <?php } ?>
<?php
}

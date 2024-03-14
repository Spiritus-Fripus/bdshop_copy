<?php
// On appelle le fichier protect.php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
// On se lie à la base de donnée qui est dans un autre dossier
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Initialise la valeur neutre des variables
$product_name = "";
$product_serie = "";
$product_author = "";
$product_publisher = "";
$product_date = "";
$product_price = "";
$product_stock = "";
$product_type_id = 0;
$product_id = 0;

// Verifie qu'il y a un id et qu'il est supérieur à 0
if (isset($_GET['id']) && $_GET['id'] > 0) {
    // Requête sql pour afficher les produit avec leur id produit correspondant
    $sql = "SELECT * FROM table_product WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    // On force le résultat a être en entier et plus en chaîne de caractére
    $stmt->bindValue(":product_id", $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    // Si la ligne retourne un résultat alors la variable prend le résultat
    if ($row = $stmt->fetch()) {
        $product_name = $row['product_name'];
        $product_serie = $row['product_serie'];
        $product_author = $row['product_author'];
        $product_publisher = $row['product_publisher'];
        $product_date = $row['product_date'];
        $product_price = $row['product_price'];
        $product_stock = $row['product_stock'];
        $product_type_id = $row['product_type_id'];
        $product_id = $row['product_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout/Modification</title>
</head>

<body class="bg-warning">
    <h1 class="text-center mt-5">Ajout ou modification d'un livre</h1>
    <div class="formulaire">
        <form action="process.php" method="post" enctype="multipart/form-data" class="d-flex align-items-center flex-column grid gap-2">
            <label for="product_name" class="fs-4 mt-2">Titre</label>
            <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product_name); ?>">

            <label for="product_serie" class="fs-4">Série</label>
            <input type="text" id="product_serie" name="product_serie" value="<?= htmlspecialchars($product_serie); ?>">

            <label for="product_author" class="fs-5">Auteur</label>
            <input type="text" id="product_author" name="product_author" value="<?= htmlspecialchars($product_author); ?>">

            <label for="product_publisher" class="fs-5">Editeur</label>
            <input type="text" id="product_publisher" name="product_publisher" value="<?= htmlspecialchars($product_publisher); ?>">

            <label for="product_date" class="fs-5">Date</label>
            <input type="date" id="product_date" name="product_date" value="<?= htmlspecialchars($product_date); ?>">

            <label for="product_price" class="fs-5">Prix</label>
            <input type="text" id="product_price" name="product_price" value="<?= htmlspecialchars($product_price); ?>">

            <label for="product_stock" class="fs-5">Stock</label>
            <input type="text" id="product_stock" name="product_stock" value="<?= htmlspecialchars($product_stock); ?>">

            <label for="product_type_id">type</label>
            <select id="product_type_id" name="product_type_id">
                <option value="0">Sélectionnez un élément dans la liste</option>
                <?php
                $sqlType = "SELECT * FROM table_type";
                $stmtType = $db->prepare($sqlType);
                $stmtType->execute();
                $recordsetType = $stmtType->fetchAll();
                foreach ($recordsetType as $rowType) { ?>
                    <option value="<?= htmlspecialchars($rowType['type_id']); ?>" <?= $rowType['type_id'] == $product_type_id ? "selected" : ""; ?>>
                        <?= htmlspecialchars($rowType['type_name']); ?>
                    </option>
                <?php } ?>
            </select>
            <!-- Exemple si on sait cb il y a de select -->
            <!-- <option value="1" <?= ($product_type_id == 1 ? "selected" : ""); ?>>Franco-Belge</option>
                <option value="2" <?php if ($product_type_id == 2) {
                                        echo "selected";
                                    } ?>>Manga</option> -->
            <!-- Même facon d'ecrire mais de facon ternaire -->
            <!-- echo si product_type_id est strictement = a 2 alors affiche le en selectionner sinon n'affiche rien -->
            <!-- <?= ($product_type_id == 2 ? "selected" : ""); ?> -->
            <!-- <option value="3" <?= ($product_type_id == 3 ? "selected" : ""); ?>>Comic</option> -->

            <!-- Input pour mettre des images -->
            <label for="product_image">Image</label>
            <input type="file" name="product_image" id="product_image">

            <!-- Champ caché pour l'id -->
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">
            <input type="submit" value="Enregistrer" class="btn btn-warning my-4">
        </form>
    </div>

</body>

</html>
<?php
// On appelle le fichier protect.php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
// On se lie a la base de donnée qui est dans un autre dossier
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Tableau pour parametrer les images plus facilement qui servira pour redimensionner ou cropper les images
$images = [
    ["prefix" => "xl", "width" => 1600, "height" => 900],
    ["prefix" => "lg", "width" => 800, "height" => 600],
    ["prefix" => "md", "width" => 400, "height" => 400],
    ["prefix" => "xs", "width" => 150, "height" => 150],
];

// On veut savoir l extension du fichier
// generatefilename a 2 paramètre : le nom de fichier et le nom qu on veut lui donner
function generateFilename($filename, $title)
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $title = str_replace(" ", "-", $title);
    // strtolower = mettre tout en minuscule
    $arrayKO = ["à", "â", "ä", " "]; // a compléter
    $arrayOK = ["a", "a", "a", "-"]; // a compléter
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $title = str_replace($arrayKO, $arrayOK, $title);
    return date("Ymdhis") . "-" . strtolower($title . "." . $extension);
}

// Permet la modification d'un champ
if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
    // Requête Update
    $sql = "UPDATE table_product SET product_name=:product_name,product_serie=:product_serie, product_author=:product_author, product_publisher=:product_publisher, product_date=:product_date, product_price=:product_price, product_stock=:product_stock , product_type_id=:product_type_id WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    // Mettre la valeur enregistrer a la bonne place
    $stmt->bindValue(":product_id", $_POST['product_id']);
} else {
    // Permet ajout dans un champ de la bdd
    // Requête insert into
    $sql = "INSERT INTO table_product (product_name,product_serie,product_author,product_publisher,product_date,product_price,product_stock,product_type_id)
        VALUES (:product_name, :product_serie, :product_author, :product_publisher, :product_date, :product_price, :product_stock, :product_type_id)";
    $stmt = $db->prepare($sql);
}
// Mettre la valeur enregistrer a la bonne place // Sortie du if pour les écrire qu'une fois au lieu de l'écrire dans chanque cas
$stmt->bindValue(":product_name", $_POST['product_name']);
$stmt->bindValue(":product_serie", $_POST['product_serie']);
$stmt->bindValue(":product_author", $_POST['product_author']);
$stmt->bindValue(":product_publisher", $_POST['product_publisher']);
$stmt->bindValue(":product_date", $_POST['product_date']);
$stmt->bindValue(":product_price", $_POST['product_price']);
$stmt->bindValue(":product_stock", $_POST['product_stock']);
$stmt->bindValue(":product_type_id", $_POST['product_type_id']);
// Pareil que les valeur du dessus (éviter les doublons)
$stmt->execute();

// Traitement de l'image
// Vérifie si on a une image et si oui on la déplace + renomme
if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "" && $_FILES['product_image']['error'] == 0) {

    // Création d'une variable pour éviter de trop répéter la ligne, c est le chemin pour aller chercher les images
    $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/product/";

    // Suppression ancienne image
    // Executer si il y a déjà une image dans la bdd
    if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
        $sql = "SELECT product_image FROM table_product WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':product_id' => $_POST['product_id']]);
        // Si il y a une image sur l'id selectionné alors
        if ($row = $stmt->fetch()) {
            if ($row['product_image'] != "") {
                foreach ($images as $image) {
                    // Vérifie la présence d'un image
                    if (file_exists($path . $image['prefix'] . "_" . $row['product_image'])) {
                        unlink($path . $image['prefix'] . "_" . $row['product_image']);
                    }
                }
            }
        }
    }

    $filename = generateFilename($_FILES['product_image']['name'], $_POST['product_name']);
    move_uploaded_file($_FILES['product_image']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . "/upload/product/" . $filename);

    // Ouvrir une image

    $prefix = "";

    foreach ($images as $image) {

        // Selon le type d'image, on la stock dans une variable
        switch ($_FILES['product_image']['type']) {
            case "image/jpeg":
                $imgSrc = imagecreatefromjpeg($path . $prefix . $filename);
                break;
            case "image/png":
                $imgSrc = imagecreatefrompng($path . $prefix . $filename);
                break;
            case "image/gif":
                $imgSrc = imagecreatefromgif($path . $prefix . $filename);
                break;
            default:
                echo "Oups";
                exit();
        }

        // On cherche a savoir la taille de l'image qu'on place dans des variables
        $sizes = getimagesize($path . $prefix . $filename);
        $imgSrcWidth = $sizes[0];
        $imgSrcHeight = $sizes[1];

        // On choisit la taille de destination de l'image, les valeurs chercher sont dans le tableau en haut dans $images à la place $image
        $imgDestWidth = $image['width'];
        $imgDestHeight = $image['height'];

        // On initialise une variable pour le resize ou non
        $toResize = true;

        // On cherche a calculé la nouvelle taille pour l'afficher convenablement
        if ($imgSrcWidth > $imgSrcHeight) {
            // Format paysage
            if ($image['width'] == $image['height']) {
                // Crop
                $imgSrcZoneX = round(($imgSrcWidth - $imgSrcHeight) / 2);
                $imgSrcZoneY = 0;
                $imgSrcZoneWidth = $imgSrcHeight;
                $imgSrcZoneHeight = $imgSrcHeight;
            } else {
                // Resize
                if ($imgSrcWidth <= $imgDestWidth) {
                    // pas de resize
                    $toResize = false;
                }
                $imgDestHeight = round(($imgDestWidth * $imgSrcHeight) / $imgSrcWidth);
                $imgSrcZoneX = 0;
                $imgSrcZoneY = 0;
                $imgSrcZoneWidth = $imgSrcWidth;
                $imgSrcZoneHeight = $imgSrcHeight;
            }
        } else {
            // Format portrait
            if ($image['width'] == $image['height']) {
                // Crop
                $imgSrcZoneX = 0;
                $imgSrcZoneY = round(($imgSrcHeight - $imgSrcWidth) / 2);
                $imgSrcZoneWidth = $imgSrcWidth;
                $imgSrcZoneHeight = $imgSrcWidth;
            } else {
                // Resize
                if ($imgSrcHeight <= $imgDestHeight) {
                    // pas de resize
                    $toResize = false;
                }
                $imgDestWidth = round(($imgDestHeight * $imgSrcWidth) / $imgSrcHeight);
                $imgSrcZoneX = 0;
                $imgSrcZoneY = 0;
                $imgSrcZoneWidth = $imgSrcWidth;
                $imgSrcZoneHeight = $imgSrcHeight;
            }
        }

        // Si $toResize est vrai alors on fait tout ca
        if ($toResize) {
            // On donne les coordonnées calculé a l'image de destination
            $imgDest = imagecreatetruecolor($imgDestWidth, $imgDestHeight);

            // On deplace les pixel de l'image source vers l'image de destination pour former la nouvelle image
            imagecopyresampled($imgDest, $imgSrc, 0, 0, $imgSrcZoneX, $imgSrcZoneY, $imgDestWidth, $imgDestHeight, $imgSrcZoneWidth, $imgSrcZoneHeight);

            // Selon le type d'image, on la stock l'image de destination dans une nouvelle variable
            switch ($_FILES['product_image']['type']) {
                case "image/jpeg":
                    imagejpeg($imgDest, $path . $image['prefix'] . "_" . $filename, 97);
                    break;
                case "image/png":
                    imagepng($imgDest, $path . $image['prefix'] . "_" . $filename, 5);
                    break;
                case "image/gif":
                    imagegif($imgDest, $path . $image['prefix'] . "_" . $filename, 97);
                    break;
            }
        } else {
            // Alors l'image de base est trop petite on l'enregistre sans la modifier avec le bon préfixe sans reprendre l'image d'avant
            copy($path . $prefix . $filename, $path . $image['prefix'] . "_" . $filename);
        }

        // Si il y a une diff entre la largeur et la hauteur alors change le préfix pour modifier l'image d'après en fonction de l'image d'avant
        if ($image['width'] != $image['height']) {
            $prefix = $image['prefix'] . "_";
        }
    }

    // On supprime le fichier d'origine
    unlink($path . $filename);
    // On supprime les données des variables pour la mémoire
    // imagedestroy($imgSrc);
    // imagedestroy($imgDest);

    // Requête update pour mettre a jour l'image
    $sql = "UPDATE table_product SET product_image=:product_image
        WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    // Mettre la valeur enregistrer avec le bon nom et type
    $stmt->bindValue(":product_image", $filename, PDO::PARAM_STR);
    // Mettre la valeur enregistrer a la bonne place // Si l'id est supp a 0 alors prendre l'id sinon prendre le dernière id créer
    $stmt->bindValue(":product_id", ($_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId()), PDO::PARAM_INT);
    $stmt->execute();
}

// Redirection
header("Location:index.php");

<?php
// On appelle le fichier protect.php
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
// On se lie a la base de donnée qui est dans un autre dossier
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// def des dimenssions d'image
$images = [
    ["prefix" => "xl", "width" => 1600, "height" => 900],
    ["prefix" => "lg", "width" => 800, "height" => 600],
    ["prefix" => "md", "width" => 400, "height" => 400],
    ["prefix" => "xs", "width" => 150, "height" => 150],
];

// On veut savoir l'extension du fichier
// generatefilename à 2 paramètre : le nom de fichier et le nom qu'on veut lui donner
function generateFilename($filename, $title)
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $title = str_replace(" ", "-", $title);
    // strtolower = mettre tout en minuscule
    $arrayKO = ["à", "â", "ä", " "]; // à compléter
    $arrayOK = ["a", "a", "a", "-"]; // à compléter
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $title = str_replace($arrayKO, $arrayOK, $title);
    return date("Ymdhis") . "-" . strtolower($title . "." . $extension);
}


// Permet la modification d'un champ
if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
    // Requête Update
    $sql = "UPDATE table_product SET product_name= :product_name,product_serie= :product_serie, product_author= :product_author, product_publisher= :product_publisher, product_date= :product_date, product_price= :product_price, product_stock= :product_stock , product_type_id= :product_type_id WHERE product_id= :product_id";
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

if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "" && $_FILES['product_image']['error'] == 0) {
    $filename = generateFilename($_FILES['product_image']['name'], $_POST['product_name']);
    move_uploaded_file($_FILES['product_image']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . "/upload/product/" . $filename);

    // redimmension de l'image
    $path = $_SERVER["DOCUMENT_ROOT"] . "/upload/product/";

    // definition de plusieurs tailles 
    foreach ($images as $image) {

        // definition de l'extension avec un switch
        switch ($_FILES['product_image']['type']) {
            case "image/jpeg":
                $imgSrc = imagecreatefromjpeg($path . $filename);
                break;
            case "image/gif":
                $imgSrc = imagecreatefromgif($path . $filename);
                break;
            case "image/png":
                $imgSrc = imagecreatefrompng($path . $filename);
                break;
            default:
                echo "Oups";
                exit();
        }

        // calcul de la taille de l'image source
        $sizes = getimagesize($path . $filename);
        $imgSrcWidth = $sizes[0];
        $imgSrcHeight = $sizes[1];

        // définition de la taille de l'image de destination
        $imgDestWidth = $image['width'];
        $imgDestHeight = $image['height'];

        if ($imgSrcWidth > $imgSrcHeight) {
            // format paysage
            if ($image['width'] == $image['height']) {
                // test crop
                $imgSrcZoneX = round(($imgSrcWidth - $imgSrcHeight) / 2);
                $imgSrczoneY = 0;
                $imgSrcZoneWidth = $imgSrcHeight;
                $imgSrcZoneHeight = $imgSrcHeight;
            } else {
                // test resize
                $imgDestHeight = round($imgSrcHeight * $imgDestWidth / $imgSrcWidth);
                $imgSrcZoneX = 0;
                $imgSrcZoneY = 0;
                $imgSrcZoneWidth = $imgSrcWidth;
                $imgSrcZoneHeight = $imgSrcHeight;
            }
        } else {
            // format portrait
            if ($image['width'] == $image['height']) {
                // test crop
                $imgSrcZoneX = 0;
                $imgSrcZoneY = round(($imgSrcHeight - $imgSrcWidth) / 2);
                $imgSrcZoneWidth = $imgDestWidth;
                $imgSrcZoneHeight = $imgSrcWitdh;
            } else {
                // test resize
                $imgDestWidth = round($imgSrcWidth * $imgDestHeight / $imgSrcHeight);
                $imgSrcZoneX = 0;
                $imgSrcZoneY = 0;
                $imgSrcZoneWidth = $imgSrcWidth;
                $imgSrcZoneHeight = $imgSrcHeight;
            }
        }

        // création d'une nouvelle image vierge 
        $imgDest = imagecreatetruecolor($imgDestWidth, $imgDestHeight);

        // injection dans l'image 
        imagecopyresampled($imgDest, $imgSrc, 0, 0, $imgSrcZoneX, $imgSrcZoneY, $imgDestWidth, $imgDestHeight, $imgSrcZoneWidth, $imgSrcZoneHeight);

        // définition du préfix de la nouvelle image
        switch ($_FILES['product_image']['type']) {
            case "image/jpeg":
                // patch + prefixe + nom du fichier + compression 
                $imgSrc = imagejpeg($imgDest, $path . $image['prefix'] . "_" . $filename, 97);
                break;
            case "image/gif":
                $imgSrc = imagegif($imgDest, $path . $image['prefix'] . "_" . $filename);
                break;
            case "image/png":
                $imgSrc = imagepng($imgDest, $path . $image['prefix'] . "_" . $filename, 5);
                break;
        }
    }

    // supprime le fichier sur le serveur
    unlink($path . $filename);

    $sql = "UPDATE table_product SET product_image= :product_image WHERE product_id = :product_id ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_image", $filename, PDO::PARAM_STR);
    $stmt->bindValue(":product_id", ($_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId()), PDO::PARAM_INT);
    $stmt->execute();
}

// Redirection
header("Location:index.php");

<?php

// fonction image
function generateFilename($filename, $title)
{
	// remplacement des caractères spéciaux
	$arrayKO = ["à", "â", "ä", " ", "é", "è"];
	$arrayOK = ["a", "a", "a", "-", "e", "e"];
	$extension = pathinfo($filename, PATHINFO_EXTENSION);
	$title = str_replace($arrayKO, $arrayOK, $title);
	// date (Y= year m=mois d=day h=heure(12h) H= heures(24h) i=minute s= seconde
	return "xs_" . date("YmdHis") . " " . strtolower($title . "." . $extension);
}

echo generateFilename("test.png", "TiNTiN le bg");

// MOVE IMG FROM TEMP TO DIRECTORY

// var_dump pour avoir les details de l'image
// var_dump($_FILES['product_image']);

//move_uploaded_files pour déplacer de tpm_name vers le dossier choisit.
move_uploaded_file($_FILES['product_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/product/" . generateFilename($_FILES['product_image']['name'], $_POST['product_name']));

header("Location: index.php");


// Formulaire CRUD.
include $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Condition pour modifications.
if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
	// // verification d'une image 
	// if ($_FILES['product_image']['name'] != "") {
	// }
	// update de la bdd
	$sql = "UPDATE table_product SET product_name = :product_name , product_serie = :product_serie, product_price = :product_price ,product_type_id= :product_type_id , product_image = :product_image WHERE product_id= :product_id";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(":product_id", $_POST['product_id']);

	// Condition pour ajout.
} else {
	$sql = "INSERT INTO table_product (product_name, product_serie, product_price , product_type_id) VALUES (:product_name, :product_serie, :product_price , :product_type_id)";
	$stmt = $db->prepare($sql);
}
// bind communs.
$stmt->bindValue(":product_name", $_POST['product_name']);
$stmt->bindValue(':product_serie', $_POST['product_serie']);
$stmt->bindValue(":product_price", $_POST['product_price']);
$stmt->bindValue(":product_type_id", $_POST['product_type_id']);
$stmt->bindValue(":product_image", generateFilename($_FILES['product_image']['name'], $_POST['product_name']));
$stmt->execute();

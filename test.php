<?php

// Morceau de form auto

if (isset($_POST['nom_de_table']) && $_POST['ID'] > 0) {
	$sql = "UPDATE nom_de_table SET";
	foreach ($_POST as $key => $value) {
		if ($key != "ID") {
			$sql .= $key . "=:" . $key;
			$bind[":" . $key] = $value;
		}
	}
	$sql .= "WHERE ID = : ID";
	$bind[':ID'] = $_POST['ID'];
}



<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
if (isset($_POST['login']) && isset($_POST['password'])) {
	$sql = "SELECT * FROM table_admin WHERE admin_login = :login";
	$stmt = $db->prepare($sql);
	$stmt->execute([":login" => $_POST['login']]);
	if ($row = $stmt->fetch()) {
		if (password_verify($_POST['password'], $row['admin_password'])) {
			session_start();
			$_SESSION["user_connected"] = "ok";
			header("Location: /admin/index.php");
			exit();
		}
	}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<div class="container">
    <h2>Admin</h2>
    <form action="login.php" method="POST">
        <label for="login"></label>
        <input type="text" name="login" placeholder="login">

        <label for="password"></label>
        <input type="password" name="password" placeholder="password">
        
        <label for="button"></label>
        <input type="submit" value="ok" name="button" class="button">

    </form>
</div>
</body>

</html>
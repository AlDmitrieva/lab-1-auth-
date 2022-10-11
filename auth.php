<?php
	session_start();
	$mysql = mysqli_connect('localhost', 'root', '', 'User_Info');
	$login = htmlspecialchars($_POST['login'] ?? '');
	$pass = htmlspecialchars($_POST['pass'] ?? '');

	$select_sql = "SELECT * FROM `Users` WHERE `LOGIN` = '$login'";

	if (mysqli_query($mysql, $select_sql)) {
		$result = mysqli_query($mysql, $select_sql);
		$user = $result->fetch_assoc();
		if (empty($user)) {
			$_SESSION['message'] = 'Такого пользователя нет в системе!';
			header('Location: /sign-in.php');
		}
		else {
			$salt = $user['SALT'];
			$pass = crypt($pass, $salt);
		}
	}
	
	$select_sql = "SELECT * FROM `Users` WHERE `LOGIN` = '$login' AND `HASH` = '$pass'";

	if (mysqli_query($mysql, $select_sql)) {
		$result = mysqli_query($mysql, $select_sql);
		$user = $result->fetch_assoc();
		if (empty($user)){
			$_SESSION['message'] = 'Такого пользователя нет в системе!';
			header('Location: /sign-in.php');
		}
		setcookie('user', $user['LOGIN'], time() + (60*60), "/");
	mysqli_close($mysql);

	header('Location: /');
}
?>
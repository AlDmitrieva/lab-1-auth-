<?php
	setcookie('user', $user['NAME'], time() - (60*60), "/");

	header('Location: /')
?>
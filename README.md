# Лаборатоная работа 1
## Задание
Спроектировать и разработать систему авторизации пользователей на протоколе HTTP
## Ход работы
- Разработаем пользовательский интерфейс и опишем пользовательские сценарии работы

![Рис. 1 - Интерфейс](https://github.com/AlDmitrieva/lab_1_auth/blob/main/%D0%B8%D0%BD%D1%82%D0%B5%D1%80%D1%84%D0%B5%D0%B9%D1%81.png)
- Опишем хореографию

![Рис. 2 - Хореография](https://github.com/AlDmitrieva/lab_1_auth/blob/main/%D0%A5%D0%BE%D1%80%D0%B5%D0%BE%D0%B3%D1%80%D0%B0%D1%84%D0%B8%D1%8F.png)
- Опишем структуру базы данных

| Название | Тип данных | Длина | Описание                                          |
|----------|------------|-------|---------------------------------------------------|
| ID       | int        |       | Ключевое поле                                     |
| NAME     | varchar    | 40    | Имя пользователя                                  |
| LOGIN    | varchar    | 40    | Логин пользователя                                |
| HASH     | varchar    | 400   | Хеш пароля и соли пользователя                    |
| SALT     | varchar    | 20    | Уникальная соль пользователя                      |

- Опишем алгоритмы 
  1. Регистрируем пользователя
 
   ```
    $login = htmlspecialchars($_POST['login'] ?? '');
	$name = htmlspecialchars($_POST['name'] ?? '');
	$pass = htmlspecialchars($_POST['pass'] ?? '');
	$pass2 = htmlspecialchars($_POST['pass2'] ?? '');
	$salt = substr(hash("sha512", time()), 10, 10);
	$pass =  crypt($pass, $salt);
	$mysql = mysqli_connect('localhost', 'root', '', 'User_Info');
	$q = "SELECT * FROM `Users` WHERE `LOGIN` = '$login'";
	$result = mysqli_query($mysql, $q);
	$user = $result->fetch_assoc();
	$q = "INSERT INTO `Users`(`ID`, `NAME`, `LOGIN`, `HASH`, `SALT`) VALUES (NULL,'$name','$login','$pass','$salt')";
	mysqli_query($mysql, $q);
	mysqli_close($mysql);
	setcookie('user', $_POST['login'], time() + (60*60), "/");
	header('Location: /index.php');
  ```
  
  2. Проверяем регистрацию на наличие ошибок
  
  ```
    $login = htmlspecialchars($_POST['login'] ?? '');
	$name = htmlspecialchars($_POST['name'] ?? '');
	$pass = htmlspecialchars($_POST['pass'] ?? '');
	$pass2 = htmlspecialchars($_POST['pass2'] ?? '');

	if (mb_strlen($login) < 3 || mb_strlen($login) > 20) {
		$_SESSION['message'] = "Недопустимая длина логина!";
		header('Location: /sign-up.php');
	}
	elseif (mb_strlen($pass) < 8 || mb_strlen($pass) > 20) {
		$_SESSION['message'] = "Недопустимая длина пароля!";
		header('Location: /sign-up.php');
	}
	elseif($pass != $pass2)
	{
		$_SESSION['message'] = "Пароли не совпадают!";
		header('Location: /sign-up.php');
	}
	else
	{
	$salt = substr(hash("sha512", time()), 10, 10);
	$pass =  crypt($pass, $salt);
	$mysql = mysqli_connect('localhost', 'root', '', 'User_Info');
	$q = "SELECT * FROM `Users` WHERE `LOGIN` = '$login'";
	$result = mysqli_query($mysql, $q);
	$user = $result->fetch_assoc();
	if(!empty($user))
	{
		$_SESSION['message'] = 'Такой пользователь уже существует!';
		header('Location: /sign-up.php');
	}
	else{
	$q = "INSERT INTO `Users`(`ID`, `NAME`, `LOGIN`, `HASH`, `SALT`) VALUES (NULL,'$name','$login','$pass','$salt')";
	mysqli_query($mysql, $q);
	mysqli_close($mysql);
	setcookie('user', $_POST['login'], time() + (60*60), "/");
	header('Location: /index.php');
  ```
  
  

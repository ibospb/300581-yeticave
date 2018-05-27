<?php
require_once ('db_connect.php');
require_once ('functions.php');

session_start();

if (isset($_SESSION['user'])) {
  $userName= $_SESSION['user']['name'];
  $userAvatar=$_SESSION['user']['avatar_path'];
  $isAuth=true;
}
else {
  $userName='';
  $userAvatar='';
  $isAuth=false;
}

// получаем список категорий
$categories=getCategoryList($con);

// валидацция формы
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST)) {
	$data = $_POST;

// проверка на обязательные поля
	$errors = [];

  if (empty($data['email'])) {
    $errors['email'] = 'Введите e-mail';
  }
  elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email']='Введите корректный email';
  }

  if (empty($data['password'])) {
    $errors['password'] = 'Введите пароль';
  }

  // запрос из БД
  $sql =
      ' SELECT *
        FROM user
        WHERE email=?
        ';
  $res = mysqli_prepare($con, $sql);
  $stmt = db_get_prepare_stmt($con, $sql, [$data['email']]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($res) {
    $user= mysqli_fetch_assoc($result);
  }
  else {
    $user= NULL;
  }

  if (!count($errors) && $user && password_verify($data['password'], $user['password']) ) {
    $_SESSION['user'] = $user;
  }
  elseif (!count($errors) && $user) {
    $errors['password']='Не верная пара логин/пароль';
  }
  if (count($errors)) {
      $content = renderTemplate('templates/login.php', ['data' => $data,
                                                      'errors' => $errors,
                                                      'categories'=>$categories]);
  }
  else {
    header("Location: /");
		exit();
  }
}
else {
  if (isset($_SESSION['user'])) {
    header("Location: /");
  }
  else {
    $content = renderTemplate('templates/login.php', ['categories'=>$categories]);
  }
}
$layoutContent = renderTemplate('templates/layout.php', [ 'content'=> $content,
                                                          'titlePage'=>'Вход',
                                                          'categories'=>$categories,
                                                          'userName'=>$userName,
                                                          'userAvatar'=>$userAvatar,
                                                          'isAuth'=>$isAuth]);
print ($layoutContent);


?>

<?php
require_once ('db_connect.php');
require_once ('functions.php');
require_once ('data.php');

$sql= 'SELECT * FROM category';
$result = mysqli_query($con,$sql);
if (!$result) {
  $error=mysqli_error($con);
  print('Ошибка БД: '. $error);
  exit();
}
else {
  $categories=mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// валидацция формы
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
	$data = $_POST;

// валидация полученных данных
	$errors = [];

  $field=$data['email'];
  if (empty($data['email'])) {
    $errors['email'] = 'Введите e-mail';
  }
  elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email']='Введите корректный email';
  }
  else {
    $sql =
        ' SELECT email
          FROM user
          WHERE email=?
          ';
    $res = mysqli_prepare($con, $sql);
    $stmt = db_get_prepare_stmt($con, $sql, [$data['email']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result)) {
      $errors['email']='Данный email уже используеться';
    }
  }

  if (empty($data['password'])) {
    $errors['password'] = 'Введите пароль';
  }
  elseif (strlen($data['password'])<5) {
    $errors['password']= 'Придумайте пароль минимум из 5 символов';
  }

  if (empty($data['name'])) {
    $errors['name'] = 'Введите имя';
  }

  if (empty($data['message'])) {
    $errors['message'] = 'Напишите как с вами связаться';
  }

// валидация изображения
if (isset($_FILES['avatar']['name']) && file_exists($_FILES['avatar']['tmp_name']) && is_uploaded_file($_FILES['avatar']['tmp_name']) ) {
  $tmp_name = $_FILES['avatar']['tmp_name'];
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $file_type = finfo_file($finfo, $tmp_name);
  if (!($file_type == 'image/jpeg' || $file_type == 'image/png')) {
    $errors['path'] = 'Загрузите картинку в формате JPG или PNG';
  }
  else {
    if ($file_type == 'image/png') {
      $fileExtension= '.png';
    }
    else {
      $fileExtension= '.jpg';
    }
    $filename = uniqid().$fileExtension;
    $data['path'] = 'img/'.$filename;
    move_uploaded_file($_FILES['avatar']['tmp_name'], $data['path']);
  }
}

	if (count($errors)) {
		$content = renderTemplate('templates/sign-up.php', ['data' => $data,
                                                    'errors' => $errors,
                                                    'categories'=>$categories]);
  }

  else {
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    // запись в БД
    if (isset($data['path'])) {
      $sql = 'INSERT INTO user  (dt_add, name, email, password, contact_details, avatar_path)
              VALUES (NOW(), ?, ?, ?, ?, ?)';
      $stmt = db_get_prepare_stmt($con, $sql, [$data['name'],$data['email'],$password, $data['message'], $data['path']]);
    }
    else {
      $sql = 'INSERT INTO user  (dt_add, name, email, password, contact_details)
              VALUES (NOW(), ?, ?, ?, ?)';
      $stmt = db_get_prepare_stmt($con, $sql, [$data['name'],$data['email'],$password, $data['message']]);
    }

    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        // переадресация
        header("Location: login.php" );
    }
    else {
      $error=mysqli_error($con);
      print('Ошибка БД: '. $error);
      exit();
    }
	}
}
else {
$content = renderTemplate('templates/sign-up.php', ['categories'=>$categories]);

}
$layoutContent = renderTemplate('templates/layout.php', ['content'=> $content,
                                                        'titlePage'=>'Регистрация',
                                                      'userName'=>$userName,
                                                    'userAvatar'=>$userAvatar,
                                                  'categories'=>$categories,
                                                'isAuth'=>$isAuth]);
print ($layoutContent);
?>
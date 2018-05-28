<?php
require_once ('db_connect.php');
require_once ('functions.php');

session_start();
$userName='';
$userAvatar='';
$isAuth=false;

if (isset($_SESSION['user'])) {
  $userName= $_SESSION['user']['name'];
  $userAvatar=$_SESSION['user']['avatar_path'];
  $isAuth=true;
  $userId= $_SESSION['user']['user_id'];
}


if (!isset($_SESSION['user'])) {
 header("HTTP/1.1 403 Forbidden");
 header("Location: login.php");
 exit();
}

// получаем список категорий
$categories=getCategoryList($con);

// валидацция формы
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lot'])) {
	$lot = $_POST['lot'];

// проверка на обязательные поля
	$errors = [];

  if (empty($lot['name'])) {
    $errors['name'] = 'Это поле надо заполнить';
  }

  $userCategory=$lot['category'];
  if (empty($userCategory) || !in_array($userCategory, array_column($categories, 'category_id'))) {
    $errors['category'] = 'Это поле надо заполнить';
  }


  if (empty($lot['message'])) {
    $errors['message'] = 'Это поле надо заполнить';
  }

  if (empty($lot['rate'])) {
    $errors['rate'] = 'Это поле надо заполнить';
  }
  elseif (($lot['rate']<=1) || !is_numeric($lot['rate'])) {
    $errors['rate'] = 'Введите число больше 0';
  }

  if (empty($lot['step'])) {
    $errors['step'] = 'Это поле надо заполнить';
  }
  elseif (($lot['step']<=1) || !is_numeric($lot['step'])) {
    $errors['step'] = 'Введите число больше 0';
  }


  if (empty($lot['date'])) {
    $errors['date'] = 'Это поле надо заполнить';
  }
  elseif (strtotime($lot['date']) <= strtotime("+1 day")) {
    $errors['date'] = 'Поставте хотябы завтрашнее число';
  }

// валидация изображения
  if (isset($_FILES['lot_img']['name']) && file_exists($_FILES['lot_img']['tmp_name']) && is_uploaded_file($_FILES['lot_img']['tmp_name'])) {
    $tmp_name = $_FILES['lot_img']['tmp_name'];
    if (!areYouImage($tmp_name)) {
      $errors['filename'] = 'Загрузите картинку в формате JPG или PNG';
    }
    else {
      $fileExtension=areYouImage($tmp_name,1);
      $lot['filename'] = uniqid().$fileExtension;
      $lot['path'] = 'img/'.$lot['filename'];
      move_uploaded_file($_FILES['lot_img']['tmp_name'], $lot['path']);
    }
  }
// в переменной $data['filename'] сохраняеться название загруженного изображения, до тех пор пока есть ошибки
  elseif (isset($lot['load_img']) && file_exists('img/'.$lot['load_img']) && areYouImage('img/'.$lot['load_img'])){
    $lot['filename']=$lot['load_img'];
    $lot['path']='img/'.$lot['filename'];
  }
  else {
    $errors['filename'] = 'Вы не загрузили файл';
  }
	if (count($errors)) {
		$content = renderTemplate('templates/add.php', ['lot' => $lot,
                                                    'errors' => $errors,
                                                    'categories'=>$categories]);

  }
	else {
    $lot['path']='img/'.$lot['filename'];
    // запись в БД
    $sql = 'INSERT INTO lot (dt_add, user_id, name, specification,
                        start_price, step_price, category_id, dt_close, pic_path)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$userId, $lot['name'], $lot['message'], $lot['rate'],$lot['step'],$lot['category'],$lot['date'],$lot['path']]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($con);

        // переадресация
        header("Location: lot.php?id=" . $lot_id);
    }
    else {
      $error=mysqli_error($con);
      print('Ошибка БД: '. $error);
      exit();
    }
	}
}
else {
  $content = renderTemplate('templates/add.php', ['categories'=>$categories]);

}
$layoutContent = renderTemplate('templates/layout.php', [ 'content'=> $content,
                                                          'titlePage'=>'Добавление лота',
                                                          'categories'=>$categories,
                                                          'userName'=>$userName,
                                                          'userAvatar'=>$userAvatar,
                                                          'isAuth'=>$isAuth]);

print ($layoutContent);
?>

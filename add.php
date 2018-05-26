<?php
require_once ('db_connect.php');
require_once ('functions.php');
require_once ('data.php');

session_start();
if (!isset($_SESSION['user'])) {
 header("Location: /");
 exit();
}

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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lot'])) {
	$lot = $_POST['lot'];

// проверка на обязательные поля
	$errors = [];

  if (empty($lot['name'])) {
    $errors['name'] = 'Это поле надо заполнить';
  }


  if (empty($lot['category']) || !isset($categories[$lot['category']]['category_id'])) {
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
      $lot['path'] = 'img/'.$filename;
      move_uploaded_file($_FILES['lot_img']['tmp_name'], $lot['path']);
    }
  }

  else {
    $errors['path'] = 'Вы не загрузили файл';
  }
	if (count($errors)) {
		$content = renderTemplate('templates/add.php', ['lot' => $lot,
                                                    'errors' => $errors,
                                                    'categories'=>$categories]);

  }
	else {

    // запись в БД
    $sql = 'INSERT INTO lot (dt_add, user_id, name, specification,
                        start_price, step_price, category_id, dt_close, pic_path)
            VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$lot['name'], $lot['message'], $lot['rate'],$lot['step'],$lot['category'],$lot['date'],$lot['path']]);
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
$layoutContent = renderTemplate('templates/layout.php', ['content'=> $content,
                                                        'titlePage'=>'Добавление лота',
                                                  'categories'=>$categories]);
print ($layoutContent);
?>

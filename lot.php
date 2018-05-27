<?php
require_once ('db_connect.php');
require_once ('functions.php');
require_once ('data.php');

session_start();

$userName='';
$isAuth=false;
$userAvatar='';

if (isset($_SESSION['user'])) {
  $userName= $_SESSION['user']['name'];
  $isAuth=true;
  $userAvatar=$_SESSION['user']['avatar_path'];
}

// проверка отправки GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id=intval($_GET['id']);
  //получаем информацию о лоте по id
  $sql =
      'SELECT lot_id, name, ru_name, pic_path, specification, start_price,
      count(bet) AS count_bet, dt_close, step_price, lot.user_id,
      GREATEST(COALESCE(MAX(bet),0), start_price) AS total_price
      FROM lot
      LEFT JOIN bet USING(lot_id)
      LEFT JOIN category  USING(category_id)
      WHERE lot.lot_id=?
      GROUP BY lot.lot_id';
  $res = mysqli_prepare($con, $sql);
  $stmt = db_get_prepare_stmt($con, $sql, [$id]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $lot = mysqli_fetch_assoc($result);

  $titlePage=$lot['name'];



}
else {
  header("HTTP/1.1 404 Forbidden");
  //header("Location: /");
  exit();
}

// получаем ставки по id //
$sql =
    'SELECT user.user_id, user.name, bet, bet.dt_add  FROM bet
    INNER JOIN user USING(user_id)
    WHERE lot_id=?
    ORDER BY bet.dt_add DESC';
$res = mysqli_prepare($con, $sql);
$stmt = db_get_prepare_stmt($con, $sql, [$id]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
  $error=mysqli_error($con);
  print('Ошибка БД: '. $error);
  exit();
}
$bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

// получаем список категорий
$sql= 'SELECT * FROM category';
$result = mysqli_query($con,$sql);
if (!$result) {
  $error=mysqli_error($con);
  print('Ошибка БД: '. $error);
  exit();
}
$categories=mysqli_fetch_all($result, MYSQLI_ASSOC);

// проверяем возможность делать ставки для данного посетителя

$userId= $_SESSION['user']['user_id'];
// ищем совпадения в таблице ставок
$rule1=in_array($userId, array_column($bets, 'user_id'));
 // ищем совпадения в таблице ставок
$rule2=($lot['user_id']!==$userId);
 // проверяем не истек ли срок лота
$rule3=strtotime($lot['dt_close'])>time();

$resolveBet=false;
if ($isAuth && !$rule1 && $rule2 && $rule3) {
   $resolveBet=true;
 }

// считаем минимальную ставку
$lot['min_bet']=$lot['start_price'];
if ($lot['total_price'] > $lot['start_price']) {
    $lot['min_bet'] = $lot['total_price']+$lot['step_price'];
}

// получаем данные из формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$userBet = $_POST['cost'];
  $errors=[];

  // проверка отправленных данных
  if (empty($userBet)) {
    $errors['cost'] = 'Введите ставку';
  }
  elseif (($userBet < $lot['min_bet']) || !is_numeric($userBet)) {
    $errors['cost'] = 'Минимальная ставка:'.$lot['min_bet'];
  }

  if (count($errors)) {
    $content = renderTemplate('templates/lot.php', ['lot' => $lot,
                                                    'errors' => $errors,
                                                    'categories'=>$categories,
                                                    'bets'=>$bets,
                                                    'resolveBet'=>$resolveBet]);
  }
  else {

    // все хорошо, записываем в БД
    $sql = 'INSERT INTO bet (dt_add, bet, lot_id, user_id)
            VALUES (NOW(), ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$userBet, $lot['lot_id'], $userId]);
    $res = mysqli_stmt_execute($stmt);
    $resolveBet=false;
    if (!$res) {
      $error=mysqli_error($con);
      print('Ошибка БД: '. $error);
      exit();
    }
  }
}
if (!isset($content)) {
  $content = renderTemplate('templates/lot.php', ['categories' => $categories,
                                                  'lot'=>$lot,
                                                  'bets'=>$bets,
                                                  'resolveBet'=>$resolveBet]);
}

$layoutContent = renderTemplate('templates/layout.php', [ 'content'=> $content,
                                                          'titlePage'=> $titlePage,
                                                          'categories'=>$categories,
                                                          'userName'=>$userName,
                                                          'userAvatar'=>$userAvatar,
                                                          'isAuth'=>$isAuth]);
print ($layoutContent);
?>

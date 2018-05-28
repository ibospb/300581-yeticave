<?php
require_once ('db_connect.php');
require_once ('functions.php');

session_start();

$userName='';
$isAuth=false;
$userAvatar='';
$userId='';


if (isset($_SESSION['user'])) {
  $userName= $_SESSION['user']['name'];
  $isAuth=true;
  $userAvatar=$_SESSION['user']['avatar_path'];
  $userId= $_SESSION['user']['user_id'];
}

// проверка отправки GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id=intval($_GET['id']);
  //получаем информацию о лоте по id
  $lot=getLot($con, $id);
  $titlePage=$lot['name'];

}
else {
  header("HTTP/1.1 404 Forbidden");
  //header("Location: /");
  exit();
}

// получаем ставки по id //
$bets=getBetList($con, $id);

// получаем список категорий
$categories=getCategoryList($con);

// проверяем возможность делать ставки для данного посетителя
$resolveBet=false;
// делал ли посетитель только что ставку
if ($lot['count_bet']!==0) {
  $rule1=$bets[0]['user_id']!==$userId;
}
// проверяем что ставок нет
elseif ($lot['count_bet']==0) {
  $rule1=true;
}
// проверяем не истек ли срок лота
$rule2=strtotime($lot['dt_close'])>time();

// создан ли лот пользователем
$rule3=$lot['user_id']!==$userId;

if ($isAuth && $rule1 && $rule2 && $rule3) {
  $resolveBet=true;
}


// считаем минимальную ставку
$lot['min_bet']=$lot['start_price'];
if ($lot['total_price'] > $lot['start_price']) {
    $lot['min_bet'] = $lot['total_price']+$lot['step_price'];
}

// получаем данные из формы
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
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
    if ($res) {
      $resolveBet=false;
      header("Location: lot.php?id=".$id);
    }
    else {
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
var_dump($isAuth && $rule1 && $rule2 && $rule3);
?>

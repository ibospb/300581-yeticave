<?php
require_once ('db_connect.php');
require_once ('functions.php');
require_once ('data.php');
// проверка отправки GET
if (isset($_GET['id'])) {
  $id=$_GET['id'];
  //получаем информацию о лоте по id
  $sql =
      'SELECT lot_id, name, ru_name, pic_path, specification, start_price,
      count(bet) AS count_bet, dt_close,
      GREATEST(COALESCE(MAX(bet),0), start_price) AS total_price,
      GREATEST(COALESCE(MAX(bet),0)+step_price, start_price) AS min_bet
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
  //var_dump($lot);
  // получаем ставки по id //
  $sql =
      'SELECT user.name, bet, bet.dt_add  FROM bet
      INNER JOIN user USING(user_id)
      WHERE lot_id=?
      ORDER BY bet.dt_add DESC';
      $res = mysqli_prepare($con, $sql);
      $stmt = db_get_prepare_stmt($con, $sql, [$id]);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
//  var_dump($bets);
}
if (!isset($lot)) {
  http_response_code(404);
  //header('HTTP/1.1 404 Not Found');
  //header('Status: 404 Not Found');
  $content='нет такого лота';
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
//var_dump($categories);
}

if (!isset($content)){
  $content = renderTemplate('templates/lot.php', ['categories' => $categories,
                                                'lot'=>$lot,
                                                'bets'=>$bets]);
}

$layoutContent = renderTemplate('templates/layout.php', ['content'=> $content,
                                                          'titlePage'=> $titlePage,
                                                        'userName'=>$userName,
                                                      'userAvatar'=>$userAvatar,
                                                    'categories'=>$categories,
                                                  'isAuth'=>$isAuth]);
print ($layoutContent);


?>

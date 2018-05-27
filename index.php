<?php
require_once ('db_connect.php');
require_once ('functions.php');

session_start();

$userName='';
$isAuth=false;
$userAvatar='';

if (isset($_SESSION['user'])) {
  $userName= $_SESSION['user']['name'];
  $isAuth=true;
  $userAvatar=$_SESSION['user']['avatar_path'];
}

// получаем список категорий
$categories=getCategoryList($con);

// получаем свежие и не закрыттые лоты
$sql= 'SELECT lot_id, name, start_price, pic_path, ru_name,
      count(bet) AS count_bet, dt_close,
      GREATEST(COALESCE(MAX(bet),0), start_price) AS total_price
      From lot
      LEFT JOIN bet USING(lot_id)
      LEFT JOIN category USING(category_id)
      WHERE dt_close>NOW()
      GROUP BY lot.lot_id
      ORDER BY lot.dt_add DESC
      LIMIT 6';
$result = mysqli_query($con,$sql);
if(!$result) {
  $error=mysqli_error($con);
  print('Ошибка БД: '. $error);
  exit();
}
else {
$lotsList=mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$content = renderTemplate('templates/index.php', [  'categories' => $categories,
                                                    'lotsList' => $lotsList]
                                                  );

$layoutContent = renderTemplate('templates/layout.php', [ 'content'=> $content,
                                                          'titlePage'=>'Главная',
                                                          'categories'=>$categories,
                                                          'userName'=>$userName,
                                                          'userAvatar'=>$userAvatar,
                                                          'isAuth'=>$isAuth]);
print ($layoutContent);


?>

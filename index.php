<?php
require_once ('functions.php');
require_once ('data.php');
date_default_timezone_set('Europe/Moscow');

/* Работа с БД */
$con = mysqli_connect('localhost', 'root', '','yeticave');
if (!$con){
  print('Ошибка подключения: '.mysqli_connect_error());
  exit();
}
else {
  $sql= 'SELECT ru_name, eng_name FROM category';
  $result = mysqli_query($con,$sql);
  if (!$result) {
    $error=mysqli_error($con);
    print('Ошибка БД: '. $error);
    exit();
  }
  else {
    $categories=mysqli_fetch_all($result, MYSQLI_ASSOC);

  }
  $sql= 'SELECT lot_id, name, start_price, pic_path, ru_name,
        count(bet) AS count_bet, dt_close,
        GREATEST(COALESCE(MAX(bet),0), start_price) AS total_price
        From lot
        LEFT JOIN bet USING(lot_id)
        LEFT JOIN category USING(category_id)
        WHERE dt_close>NOW()
        GROUP BY lot.lot_id
        ORDER BY lot.dt_add DESC';
  $result = mysqli_query($con,$sql);
  if(!$result) {
    $error=mysqli_error($con);
    print('Ошибка БД: '. $error);
    exit();
  }
  else {
  $lots__list=mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
}
$content = renderTemplate('templates/index.php', ['categories' => $categories,
                                                    'lots__list' => $lots__list]
                                                  );

$layout_content = renderTemplate('templates/layout.php', ['content'=> $content,
                                                          'title_page'=> $title_page,
                                                        'user_name'=>$user_name,
                                                      'user_avatar'=>$user_avatar,
                                                    'categories'=>$categories,
                                                  'is_auth'=>$is_auth]);
print ($layout_content);
/*$a=0;
while ($a++ < 100) {
  echo total_bet($a).'<br>';
}
*/

?>

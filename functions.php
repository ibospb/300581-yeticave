<?


// пользовательские функции

function format_price ($price) {
  if ($price>=1000) {
    $price= number_format((ceil($price)),0,'.',' ');
  }
  else {
    $price= ceil($price);
  }
return $price.' <b class="rub">р</b>';
}

// функция- шаблонизатор

function renderTemplate ($path, $data) {
  $out_content='';
  if (file_exists($path)) {
    ob_start();
    extract ($data);
    require ($path);
    $out_content= ob_get_clean();
  }

  return $out_content;
}

/*function total_bet($count_bet) {
$out='Стартовая цена';
if (($count_bet%10>=5 && $count_bet%10<=9) or ($count_bet>=10 && $count_bet<=20)) {
  $out=$count_bet.' ставок';
} elseif ($count_bet%10<=4 && $count_bet%10>=2) {
    $out=$count_bet.' ставки';
  } elseif ($count_bet!=0){
    $out=$count_bet.' ставка';
  }
  return $out; //1 ка 2,3,4 ки 5,6,7,8,9, ставок 21ка 22ки 23ки 25ставок
}
*/

/* функция склоняет слово "ставка" в зависимости от количества ставок
если ставок нет (кол-во ставок равно 0), то выводит "Стартовая цена" */
function total_bet($count_bet) {
$out='Стартовая цена';
if ($count_bet%10>=2 && $count_bet%10<=4 && ($count_bet%100<10 or $count_bet%100>=20)) {
  $out=$count_bet.' ставки';
} elseif ($count_bet%10==1 && $count_bet%100!=11) {
    $out=$count_bet.' ставка';
  } elseif ($count_bet!=0){
    $out=$count_bet.' ставок';
  }
  return $out; //1 ка 2,3,4 ки 5,6,7,8,9,..20 ставок 21 ка 22..24 ки 25..30 ок
}

function timerLot ($closing_time) {
  $now=time();
  $second= strtotime($closing_time)-$now;
  $hours= floor($second/3600);
  $minutes= floor(($second%3600)/60);
  $days= floor($second/86400);

  if ($second<=0) {
    $timer='время закончилось';

  } elseif ( $days<=1) {
    $timer= $hours.' ч '.$minutes.' мин ';
  } elseif ($days<=7 && $days>1) {
    $timer= $days.' дня';
  } else {
    $timer= date('d-m-Y', strtotime($closing_time));
  }

  return $timer;
}

<?


// пользовательские функции

function formatPrice ($price) {
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
  $outContent='';
  if (file_exists($path)) {
    ob_start();
    extract ($data);
    require ($path);
    $outContent= ob_get_clean();
  }

  return $outContent;
}

/* функция склоняет слово "ставка" в зависимости от количества ставок
если ставок нет (кол-во ставок равно 0), то выводит "Стартовая цена" */
function totalBet($countBet) {
$out='Стартовая цена';
if ($countBet%10>=2 && $countBet%10<=4 && ($countBet%100<10 or $countBet%100>=20)) {
  $out=$countBet.' ставки';
} elseif ($countBet%10==1 && $countBet%100!=11) {
    $out=$countBet.' ставка';
  } elseif ($countBet!=0){
    $out=$countBet.' ставок';
  }
  return $out; //1 ка 2,3,4 ки 5,6,7,8,9,..20 ставок 21 ка 22..24 ки 25..30 ок
}

function timerLot ($closingTime) {
  $now=time();
  $second= strtotime($closingTime)-$now;
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
    $timer= date('d-m-Y', strtotime($closingTime));
  }

  return $timer;
}

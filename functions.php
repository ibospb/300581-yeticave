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

// функция отсчета времени до начала следующих суток

/* function time_to_midnight () {
  $midnight = strtotime('tomorrow');
  $second= $midnight-time();
  $hours = floor($second/3600);
  $minutes =floor(($second% 3600)/60);
  return $hours.':'.$minutes;

} */

// функция отсчета времени до окончания торгов
// входные данные: дата окончания торгов
// функция возвращает: 7d<'d-m-Y' , 1d <'j'<= 7d , 'h:m' <= 1d
/* function timerLot ($closing_time) {
  $secs_to_end= strtotime($closing_time)-time();
  if ($secs_to_end<=86400) {
   $timer= date('g ч m мин', $secs_to_end);
  }
  elseif ($secs_to_end>=86400 && $secs_to_end<=604800) {
     $timer=date('j дня', $secs_to_end);
  }
  else {
    $timer= date('d-m-Y', strtotime($closing_time));
  }
  return $timer;
} */

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
    $timer= date('d:m:Y', strtotime($closing_time));
  }

  return $timer;
}

<?
// установка временной зоны
date_default_timezone_set('Europe/Moscow');


// файл графика или нет (true/false)('image/png' или 'image/png') входные данные: сылка на файл $path
//  '.png' или '.jpg' или false, если указан второй аргумент

function  areYouImage ($path, $value='') {
  $result=false;
  $file_type=mime_content_type($path);
  if ($file_type == 'image/jpeg') {
    $result= '.jpg';
  }
  if ($file_type == 'image/png') {
    $result= '.png';
  }
  if (empty($value) && $result) {
    $result= true;
  }

return $result;
}

// функция фоматирования цены
function formatPrice ($price) {
  if ($price>=1000) {
    $price= number_format((ceil($price)),0,'.',' ');
  }
  else {
    $price= ceil($price);
  }
return $price;
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
    $timer= date('d:m:Y', strtotime($closingTime));
  }

  return $timer;
}

// время ставки в человеческом формате (5 минут назад, час назад и т.д.).
function timerBet ($dt_add) {
  $now=time();
  $second= $now-strtotime($dt_add);
  $minutes= floor($second/60);
  $hours= floor($second/3600);

if ($minutes<1440 && $minutes>60) {
  $timer= $hours.' часа назад';
}
elseif ($minutes<=60) {
  $timer= $minutes.' минут назад';
}
else {
  $timer= date('d.m.Y \\в H:m', strtotime($dt_add)) ;
}

return $timer;
}

// Запрос списка ставок по Id лота
function getBetList ($con, $id) {
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

return $bets;
}

// запрос информации о лоте по его id
function getLot ($con, $id) {
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
  if (!$result) {
    $error=mysqli_error($con);
    print('Ошибка БД: '. $error);
    exit();
  }
$lot = mysqli_fetch_assoc($result);

return $lot;
}

// запрос списка категорий
function getCategoryList($con) {
  $sql= 'SELECT * FROM category';
  $result = mysqli_query($con,$sql);
  if (!$result) {
    $error=mysqli_error($con);
    print('Ошибка БД: '. $error);
    exit();
  }
  $categories=mysqli_fetch_all($result, MYSQLI_ASSOC);
return $categories;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

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
  if (file_exists($path)) {
    ob_start();
    extract ($data);
    require ($path);
    $out_content= ob_get_clean();
  }
  else {
    $out_content='';
  }
  return $out_content;
}

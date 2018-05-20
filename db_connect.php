<?php

$db= [
    'host'=>'localhost',
    'user'=>'root',
    'password'=>'',
    'namebase'=>'yeticave'
];

$con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['namebase']);
if (!$con){
  print('Ошибка подключения: '.mysqli_connect_error());
  exit();
}

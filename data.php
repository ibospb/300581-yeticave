<?php
date_default_timezone_set('Europe/Moscow');
$isAuth = (bool) rand(0, 1);

$userName = 'Константин';
$userAvatar = 'img/user.jpg';
$titlePage= 'Главная';





/*
// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];

$categories = [
    [
        'ru_name' => 'Доски и лыжи',
        'eng_name' => 'boards'
    ],
    [
        'ru_name' => 'Крепления',
        'eng_name' => 'attachment'
    ],
    [
        'ru_name' => 'Ботинки',
        'eng_name' => 'boots'
    ],
    [
        'ru_name' => 'Одежда',
        'eng_name' => 'clothing'
    ],
    [
        'ru_name' => 'Инструменты',
        'eng_name' => 'tools'
    ],
    [
        'ru_name' => 'Разное',
        'eng_name' => 'other'
    ]
];
*/
/*
$lots__list = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => $categories[0]['ru_name'],
        'price' => '10999',
        'pic' => 'img/lot-1.jpg',
        'closing_time' =>'2018-05-05'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0]['ru_name'],
        'price' => '159999',
        'pic' => 'img/lot-2.jpg',
        'closing_time' =>'2018-05-07'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1]['ru_name'],
        'price' => '8000',
        'pic' => 'img/lot-3.jpg',
        'closing_time' =>'2018-05-10'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2]['ru_name'],
        'price' => '10999',
        'pic' => 'img/lot-4.jpg',
        'closing_time' =>'2018-05-15'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3]['ru_name'],
        'price' => '7500',
        'pic' => 'img/lot-5.jpg',
        'closing_time' =>'2018-05-06'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories[5]['ru_name'],
        'price' => '5400',
        'pic' => 'img/lot-6.jpg',
        'closing_time' =>'2018-05-09'
    ]
];
*/

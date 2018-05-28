USE yeticave;

INSERT INTO category(ru_name, eng_name)
VALUES  ('Доски и лыжи','boards'),
        ('Крепления','attachment'),
        ('Ботинки','boots'),
        ('Одежда','clothing'),
        ('Инструменты','tools'),
        ('Разное','other');

INSERT INTO user (name, email, dt_add, password, contact_details)
VALUES  ('Игнат','ignat.v@gmail.com','2018-03-28','$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka','Значимость этих проблем настолько очевидна, что реализация намеченных плановых заданий не оставляет шанса для поэтапного и последовательного развития общества. Диаграммы связей формируют глобальную экономическую сеть и при этом - ограничены'),
        ('Леночка','kitty_93@li.ru','2017-10-17','$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa','Для современного мира внедрение современных методик создает необходимость включения в производственный план целого ряда внеочередных мероприятий с учетом комплекса направлений прогрессивного развития.'),
        ('Руслан','warrior07@mail.ru','2018-02-11','$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW','Современные технологии достигли такого уровня, что сплоченность команды профессионалов способствует повышению качества новых предложений.');

INSERT INTO lot (name, specification, start_price, step_price, category_id, user_id, user_win, dt_close, dt_add, pic_path)
VALUES  ('2014 Rossignol District Snowboard', 'Все самое лучшее',  10999,   500,  1,1,DEFAULT,   '2018-06-11','2018-05-09','img/lot-1.jpg'),
        ('DC Ply Mens 2016/2017 Snowboard',   'Б\У чутка пахнет',  159999,  1000, 1,2,DEFAULT,'2018-06-03','2018-05-02','img/lot-2.jpg'),
        ('Крепления Union Contact Pro 2015 года размер L/XL','ух', 8000,    300,  2,1,DEFAULT,   '2018-06-30','2018-04-29','img/lot-3.jpg'),
        ('Ботинки для сноуборда DC Mutiny Charocal','надо брать',  10999,   100,  3,2,DEFAULT,'2018-06-15','2018-05-05','img/lot-4.jpg'),
        ('Куртка для сноуборда DC Mutiny Charocal','хороший слон', 7500,    50,   4,2,DEFAULT,   '2018-06-05','2018-05-03','img/lot-5.jpg'),
        ('Маска Oakley Canopy','Есть кондиционер и бинты',         5400,    150,  6,3,DEFAULT,   '2018-06-09','2018-05-07','img/lot-6.jpg');

INSERT INTO bet (bet, dt_add, lot_id, user_id)
VALUES  (11500,'2018-05-08',5,2),
        (11000,'2018-05-09',3,1),
        (12000,'2018-05-09',4,2),
        (25000,'2018-05-08',3,1),
        (6500,'2018-05-05',6,1),
        (3000,'2018-05-06',5,3),
        (5000,'2018-05-10',3,1),
        (10000,'2018-05-16',1,2),
        (12000,'2018-05-21',4,2),
        (2900,'2018-05-10',2,1),
        (7690,'2018-05-18',4,3),
        (10000,'2018-05-07',1,1);



          -- запрос всех категорий
USE yeticave;
SELECT ru_name, eng_name FROM category;


  /* получить самые новые, открытые лоты.
  Каждый лот должен включать название, стартовую цену,
  ссылку на изображение, цену, количество ставок, название категории
  */
USE yeticave;
SELECT lot_id, name, start_price, pic_path, ru_name,
-- считаем кол-во ставок на лот
count(bet) AS count_bet,
-- считаем конечную цену
GREATEST(COALESCE(MAX(bet),0), start_price) AS total_price
From lot
LEFT JOIN bet USING(lot_id)
LEFT JOIN category USING(category_id)
-- убираем записи закрытых лотов
WHERE dt_close>NOW()
-- групировка по ID
GROUP BY lot.lot_id
-- сортируем по дате
ORDER BY lot.dt_add DESC;


   /* показать лот по его id. Получите также
   название категории, к которой принадлежит лот
   */
USE yeticave;
SELECT lot_id, name, ru_name FROM lot
INNER JOIN category  USING(category_id)
WHERE lot_id=2;


    /* обновить название лота по его идентификатору */
USE yeticave;
UPDATE lot SET name='Blizzard IQon 7400 Classic'
WHERE lot_id=2;


    /* получить список самых свежих ставок
   для лота по его идентификатору
   */
 USE yeticave;
 SELECT bet, dt_add FROM bet
 WHERE lot_id=3
 ORDER BY dt_add DESC;

    /* добавляем новый лот
    */
USE yeticave;
INSERT INTO lot (name, specification, start_price, step_price, category_id, user_id, user_win, dt_close, dt_add, pic_path)
VALUES  ('Линза Axiina ESS CHALLENGE S 2.0 DOUBLEFLEX hicon S2', 'Как принято считать, представители современных социальных резервов освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, указаны как претенденты на роль ключевых факторов. Приятно, граждане, наблюдать, как стремящиеся вытеснить традиционное производство, нанотехнологии представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть заблокированы в рамках своих собственных рациональных ограничений. Современные технологии достигли такого уровня, что начало повседневной работы по формированию позиции предоставляет широкие возможности для экономической целесообразности принимаемых решений.',  1999,   500,  1,1,DEFAULT,   '2018-05-30','2018-05-13','img/lot-7.jpg')

USE yeticave;

INSERT INTO category(ru_name, eng_name)
VALUES  ('Доски и лыжи','boards'),
        ('Крепления','attachment'),
        ('Ботинки','boots'),
        ('Одежда','clothing'),
        ('Инструменты','tools'),
        ('Разное','other');

INSERT INTO user (name, email,password)
VALUES  ('Игнат','ignat.v@gmail.com','$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
        ('Леночка','kitty_93@li.ru','$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
        ('Руслан','warrior07@mail.ru','$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

INSERT INTO lot (name, specification, start_price, step_price, category_id, user_id, user_win, dt_close, dt_add, pic_path)
VALUES  ('2014 Rossignol District Snowboard','Все самое лучшее','10999','500','1','1','2','2018-05-11','2018-05-09','img/lot-1.jpg'),
        ('DC Ply Mens 2016/2017 Snowboard','','159999','1000','1','2','2','2018-05-11','2018-05-02','img/lot-2.jpg'),
        ('Крепления Union Contact Pro 2015 года размер L/XL','','8000','300','2','1','1','2018-05-10','2018-04-29','img/lot-3.jpg'),
        ('Ботинки для сноуборда DC Mutiny Charocal','','10999','100','3','2','3','2018-05-15','2018-05-05','img/lot-4.jpg'),
        ('Куртка для сноуборда DC Mutiny Charocal','','7500','50','4','2','2','2018-05-06','2018-05-03','img/lot-5.jpg'),
        ('Маска Oakley Canopy','','5400','150','6','3','3','2018-05-09','2018-05-07','img/lot-6.jpg');

INSERT INTO bet (bet, dt_add, lot_id, user_id)
VALUES  ('11500','2018-05-08','5','2'),
        ('11000','2018-05-09','3','1'),
        ('12000','2018-05-09','4','2'),
        ('200','2018-05-08','3','1'),
        ('6500','2018-05-05','6','1'),
        ('1050','2018-05-06','1','3'),
        ('10000','2018-05-07','6','2');



          -- запрос всех категорий
USE yeticave;
SELECT ru_name FROM category;


    /* получить самые новые, открытые лоты.
   Каждый лот должен включать название, стартовую цену,
   ссылку на изображение, цену, количество ставок, название категории
   */
USE yeticave;
SELECT name, start_price, pic_path, max_bet, total_bet, ru_name
FROM lot
LEFT JOIN
  -- групировка по ID лота и считаем кол-во ставок на лот и самую высокую ставку на лот
  (SELECT lot_id, count(bet) AS total_bet, MAX(bet) AS max_bet FROM bet
  GROUP BY lot_id) AS bet
  USING(lot_id)
INNER JOIN category USING(category_id)
-- убираем записи закрытых лотов и проверяем что МАХ ставка больше начальной цены+шаг цены
WHERE dt_close> NOW() AND (total_bet IS NULL OR max_bet>=start_price)
-- сортируем по дате добавления лота
ORDER BY lot.dt_add DESC;


   /* показать лот по его id. Получите также
   название категории, к которой принадлежит лот
   */
USE yeticave;
SELECT lot_id, name, ru_name FROM lot
INNER JOIN category  USING(category_id)
WHERE lot_id='2';


    /* обновить название лота по его идентификатору */
USE yeticave;
UPDATE lot SET name='Blizzard IQon 7400 Classic'
WHERE lot_id='2';


    /* получить список самых свежих ставок
   */
 USE yeticave;
 SELECT bet, bet.dt_add FROM bet
 INNER JOIN lot USING(lot_id)
 WHERE lot_id='3'
 ORDER BY bet.dt_add DESC;

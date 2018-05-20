<main class="container">
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">

      <!-- PHP код для показа промо категорий -->
      <?foreach ($categories as $key => $value): ?>
        <li class="promo__item promo__item--<?=$value['eng_name']?>">
              <a class="promo__link" href="all-lots.html"><?=$value['ru_name']?></a>
          </li>
      <?endforeach;?>

    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">

        <!-- код для показа списка лотов -->
        <?foreach ($lotsList as $key => $value): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$value['pic_path']?>" width="350" height="260" alt="<?=htmlspecialchars($value['name'])?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=$value['ru_name']?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$value['lot_id']?>"><?=htmlspecialchars($value['name'])?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount"><?=totalBet($value['count_bet'])?></span>
                        <span class="lot__cost"><?=formatPrice($value['total_price'])?><b class="rub">р</b></span>
                    </div>
                    <div class="lot__timer timer">
                      <?=timerLot($value['dt_close'])?>
                    </div>
                </div>
            </div>
        </li>
        <?endforeach;?>

    </ul>
</section>
</main>

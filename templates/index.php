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
        <?foreach ($lots__list as $key => $value): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$value['pic']?>" width="350" height="260" alt="<?=htmlspecialchars($value['name'])?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=$value['category']?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.html"><?=htmlspecialchars($value['name'])?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=format_price($value['price'])?></span>
                    </div>
                    <div class="lot__timer timer">
                      <?=time_to_midnight()?>
                    </div>
                </div>
            </div>
        </li>
        <?endforeach;?>

    </ul>
</section>

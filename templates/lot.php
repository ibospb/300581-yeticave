
<nav class="nav">
  <ul class="nav__list container">
    <!-- PHP код для показа категорий -->
    <?foreach ($categories as $key => $value): ?>
      <li class="nav__item">
          <a href="all-lots.html"><?=$value['ru_name']?></a>
      </li>
    <?endforeach;?>
  </ul>
</nav>
  <section class="lot-item container">
    <h2><?=htmlspecialchars($lot['name'])?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="<?=$lot['pic_path']?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name'])?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=$lot['ru_name']?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['specification'])?></p>
      </div>
      <div class="lot-item__right">
        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=timerLot($lot['dt_close'])?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=formatPrice($lot['total_price'])?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=formatPrice($lot['min_bet'])?></span>
            </div>
          </div>
          <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
            <p class="lot-item__form-item">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="number" name="cost" placeholder="<?=$lot['min_bet']?>">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
        </div>
        <div class="history">
          <h3>История ставок (<span><?=$lot['count_bet']?></span>)</h3>
          <table class="history__list">
            <?foreach ($bets as $key => $value): ?>
            <tr class="history__item">
              <td class="history__name"><?=$value['name']?></td>
              <td class="history__price"><?=formatPrice($value['bet'])?> р</td>
              <td class="history__time"><?=timerBet($value['dt_add'])?></td>
            </tr>
            <?endforeach;?>
          </table>
        </div>
      </div>
    </div>
  </section>

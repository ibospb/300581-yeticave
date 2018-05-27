  <nav class="nav">
    <ul class="nav__list container">
      <!-- PHP код для показа категорий -->
      <?foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?=$val['ru_name']?></a>
        </li>
      <?endforeach;?>
    </ul>
  </nav>
  <? $classname= isset($errors) ? 'form--invalid' : ''; ?>
  <form class="form form--add-lot container <?=$classname?>" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">

      <?  $field='name';
          $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
          $value= isset($lot[$field]) ? $lot[$field] : '';
          $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
      <div class="form__item <?=$classname?>"> <!-- form__item--invalid -->
        <label for="lot-name">Наименование</label>
        <input id="lot-name" type="text" name="lot[name]" placeholder="Введите наименование лота" value=<?=htmlspecialchars($value);?>>
        <span class="form__error"><?=$errorMsg?></span>
      </div>

      <? $field='category';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($lot[$field]) ? $lot[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
      <div class="form__item <?=$classname;?>">
        <label for="category">Категория</label>
        <select id="category" name="lot[category]" >
          <option value="">Выберите категорию</option>

            <?foreach ($categories as $key => $val): ?>
              <option value="<?=$val['category_id'];?>"<?=(isset($lot['category']) && ($val['category_id']==$lot['category'])) ? 'selected' : '';?>><?=$val['ru_name'];?></option>
            <?endforeach;?>
        </select>
        <span class="form__error"><?=$errorMsg?></span>
      </div>
    </div>

    <? $field='message';
    $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
    $value= isset($lot[$field]) ? $lot[$field] : '';
    $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item form__item--wide <?=$classname;?>">
      <label for="message">Описание</label>
      <textarea id="message" name="lot[message]" placeholder="Напишите описание лота"><?=htmlspecialchars($value);?></textarea>
      <span class="form__error"><?=$errorMsg?></span>
    </div>

    <?  $field='path';
        $classname= isset($lot[$field]) ? 'form__item--uploaded' : 'form__item--invalid';
        $value= isset($lot[$field]) ? $lot[$field] : '';
        $errorMsg=isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item form__item--file <?=$classname;?>"> <!-- form__item--uploaded -->
      <label>Изображение</label>

      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img src="" width="113" height="113" alt="Изображение лота">
        </div>
      </div>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="photo2" name="lot_img" value="">
        <span class="form__error"><?=$errorMsg?></span>
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
    </div>
    <div class="form__container-three">

      <?  $field='rate';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($lot[$field]) ? $lot[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
      <div class="form__item form__item--small <?=$classname;?>">
        <label for="lot-rate">Начальная цена</label>
        <input id="lot-rate" type="number" name="lot[rate]" placeholder="0" value=<?=htmlspecialchars($value);?>>
        <span class="form__error"><?=$errorMsg?></span>
      </div>

      <?  $field='step';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($lot[$field]) ? $lot[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
      <div class="form__item form__item--small <?=$classname;?>">
        <label for="lot-step">Шаг ставки</label>
        <input id="lot-step" type="number" name="lot[step]" placeholder="0" value=<?=htmlspecialchars($value);?>>
        <span class="form__error"><?=$errorMsg?></span>
      </div>

      <?  $field='date';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($lot[$field]) ? $lot[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
      <div class="form__item <?=$classname;?>">
        <label for="lot-date">Дата окончания торгов</label>
        <input class="form__input-date" id="lot-date" type="date" name="lot[date]" value=<?=htmlspecialchars($value);?>>
        <span class="form__error"><?=$errorMsg?></span>
      </div>
    </div>
    <span class="form__error form__error--bottom"><?=isset($errors) ?'Пожалуйста, исправьте ошибки в форме.' : '';?></span>

    <button type="submit" class="button">Добавить лот</button>
  </form>

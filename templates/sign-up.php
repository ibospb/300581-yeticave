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
  <form class="form container <?=$classname?>" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>

    <?  $field='email';
        $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
        $value= isset($data[$field]) ? $data[$field] : '';
        $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item <?=$classname?>"> <!-- form__item--invalid -->
      <label for="email">E-mail*</label>
      <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value?>" >
      <span class="form__error"><?=$errorMsg?></span>
    </div>

    <?  $field='password';
        $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
        $value= isset($data[$field]) ? $data[$field] : '';
        $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item <?=$classname?>">
      <label for="password">Пароль*</label>
      <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?=$value?>">
      <span class="form__error"><?=$errorMsg?></span>
    </div>

    <?  $field='name';
        $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
        $value= isset($data[$field]) ? $data[$field] : '';
        $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item <?=$classname?>">
      <label for="name">Имя*</label>
      <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$value?>">
      <span class="form__error"><?=$errorMsg?></span>
    </div>

    <?  $field='message';
        $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
        $value= isset($data[$field]) ? $data[$field] : '';
        $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item <?=$classname?>">
      <label for="message">Контактные данные*</label>
      <textarea id="message" name="message" placeholder="Напишите как с вами связаться" ><?=$value?></textarea>
      <span class="form__error"><?=$errorMsg?></span>
    </div>

    <?  $field='path';
        $value= isset($data[$field]) ? $data[$field] : '';
        $classname= isset($data[$field]) ? 'form__item--uploaded' : 'form__item--invalid';
        $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
    <div class="form__item form__item--file form__item--last <?=$classname?>">
      <label>Аватар</label>
      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img src="<?=$value;?>" width="113" height="113" alt="Ваш аватар">
        </div>
      </div>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="photo2" value="" name="avatar">
        <span class="form__error"><?=$errorMsg?></span>
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
    </div>
    <span class="form__error form__error--bottom"><?=isset($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : '';?></span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
  </form>

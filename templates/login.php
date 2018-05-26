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
<form class="form container <?=$classname?>" action="" method="post"> <!-- form--invalid -->
  <h2>Вход</h2>

  <?  $field='email';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($data[$field]) ? $data[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
  <div class="form__item <?=$classname?>"> <!-- form__item--invalid -->
    <label for="email">E-mail*</label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value?>">
    <span class="form__error"><?=$errorMsg?></span>
  </div>

  <?  $field='password';
      $classname= isset($errors[$field]) ? 'form__item--invalid' : '';
      $value= isset($data[$field]) ? $data[$field] : '';
      $errorMsg= isset($errors[$field]) ? $errors[$field] : '';?>
  <div class="form__item form__item--last <?=$classname?>">
    <label for="password">Пароль*</label>
    <input id="password" type="text" name="password" placeholder="Введите пароль" >
    <span class="form__error"><?=$errorMsg?></span>
  </div>
  <button type="submit" class="button">Войти</button>
</form>

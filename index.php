<?php
require_once ('functions.php');
require_once ('data.php');

$content = renderTemplate('templates/index.php', ['categories' => $categories,
                                                    'lots__list' => $lots__list]
                                                  );
$layout_content = renderTemplate('templates/layout.php', ['content'=> $content,
                                                          'title_page'=> $title_page,
                                                        'user_name'=>$user_name,
                                                      'user_avatar'=>$user_avatar,
                                                    'categories'=>$categories,
                                                  'is_auth'=>$is_auth]);
print ($layout_content);

?>

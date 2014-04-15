<?php
/**
 * Created by PhpStorm.
 * User: salerat
 * Date: 4/9/14
 * Time: 3:20 PM
 */

namespace kaz;
require 'Kaz.php';

if(!empty($_POST)) {
    $kaz = new Kaz($_POST['word']);
    $resultArray = $kaz->getFlectiveClass();
}
$word = '';
if(!empty($_POST['word'])) {
    $word=$_POST['word'];
}
echo '<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">

  <title>Генерация словоформ</title>

  <link rel="stylesheet" href="css/styles.css">

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
<form method="POST">
  Введите слово в именительном падеже (пример: адам) <input type="text" name="word" value="'.$word.'"><br>
  <input type="submit" value="Сгенерировать">
</form>
';
if(!empty($resultArray)) {
    echo '<table border="1">
    <tr>
        <td><b>Конфигурация слово образования</b></td>
    <td><b>Форма слова</b></td>
    </tr>';
    foreach($resultArray as $res) {
        echo '
    <tr>
        <td>'.$res['type'].'</td>
    <td>'.$res['word'].'</td>
    </tr>';
    }
    echo '</table>';
}
echo '
</body>
</html>
';
<?php
/**
 * Created by PhpStorm.
 * User: salerat
 * Date: 4/15/14
 * Time: 12:21 PM
 */

//✔   После сонорных й, у тоже следуют дополнительные гласные ы или i:

namespace kaz;

require 'Kaz.php';

$test2Arr = array(
    array('rus' => 'моя бабушка', 'type' => ';possessive_s_1;', 'wordTransform' => 'әжем', 'word' => 'әже'),
    array('rus' => 'мой ребёнок', 'type' => ';possessive_s_1;', 'wordTransform' => 'балам', 'word' => 'бала'),
    array('rus' => 'моё зеркало', 'type' => ';possessive_s_1;', 'wordTransform' => 'айнам', 'word' => 'айна'),
    array('rus' => 'моя полка', 'type' => ';possessive_s_1;', 'wordTransform' => 'сөрем', 'word' => 'сөре'),
    array('rus' => 'моя комната', 'type' => ';possessive_s_1;', 'wordTransform' => 'бөлмем', 'word' => 'бөлме'),
    array('rus' => 'мой город', 'type' => ';possessive_s_1;', 'wordTransform' => 'қалам', 'word' => 'қала'),
    array('rus' => 'мой друг', 'type' => ';possessive_s_1;', 'wordTransform' => 'досым', 'word' => 'дос'),
    array('rus' => 'моя бумага', 'type' => ';possessive_s_1;', 'wordTransform' => 'қағазым', 'word' => 'қағаз'),
    array('rus' => 'мои часы', 'type' => ';possessive_s_1;', 'wordTransform' => 'сағатым', 'word' => 'сағат'),
    array('rus' => 'мой стол', 'type' => ';possessive_s_1;', 'wordTransform' => 'үстелiм', 'word' => 'үстел'),
    array('rus' => 'мой словарь', 'type' => ';possessive_s_1;', 'wordTransform' => 'сөздiгiм', 'word' => 'сөздiг'),
    array('rus' => 'моя квартира', 'type' => ';possessive_s_1;', 'wordTransform' => 'пәтерiм', 'word' => 'пәтер'),
    array('rus' => 'мой чай', 'type' => ';possessive_s_1;', 'wordTransform' => 'шайым', 'word' => 'шай'),
    array('rus' => 'моя гора', 'type' => ';possessive_s_1;', 'wordTransform' => 'тауым', 'word' => 'тау'),
    array('rus' => 'мой дом', 'type' => ';possessive_s_1;', 'wordTransform' => 'үйiм', 'word' => 'үй'),
    array('rus' => 'наш город', 'type' => ';possessive_p_1;', 'wordTransform' => 'қаламыз', 'word' => 'қала'),
    array('rus' => 'наш ребёнок', 'type' => ';possessive_p_1;', 'wordTransform' => 'баламыз', 'word' => 'бала'),
    array('rus' => 'наше зеркало', 'type' => ';possessive_p_1;', 'wordTransform' => 'айнамыз', 'word' => 'айна'),
    array('rus' => 'наша полка', 'type' => ';possessive_p_1;', 'wordTransform' => 'сөремiз', 'word' => 'сөре'),
    array('rus' => 'наша комната', 'type' => ';possessive_p_1;', 'wordTransform' => 'бөлмемiз', 'word' => 'бөлме'),
    array('rus' => 'наша бабушка', 'type' => ';possessive_p_1;', 'wordTransform' => 'әжемiз', 'word' => 'әже'),
    array('rus' => 'наш друг', 'type' => ';possessive_p_1;', 'wordTransform' => 'досымыз', 'word' => 'дос'),
    array('rus' => 'наша бумага', 'type' => ';possessive_p_1;', 'wordTransform' => 'қағазымыз', 'word' => 'қағаз'),
    array('rus' => 'наши часы', 'type' => ';possessive_p_1;', 'wordTransform' => 'сағатымыз', 'word' => 'сағат'),
    array('rus' => 'наш стол', 'type' => ';possessive_p_1;', 'wordTransform' => 'үстелiмiз', 'word' => 'үстел'),
    array('rus' => 'наш словарь', 'type' => ';possessive_p_1;', 'wordTransform' => 'сөздiгiмiз', 'word' => 'сөздiг'),
    array('rus' => 'наша квартира', 'type' => ';possessive_p_1;', 'wordTransform' => 'пәтерiмiз', 'word' => 'пәтер'   ),
    array('rus' => 'мой дедушка', 'type' => ';possessive_s_1;', 'wordTransform' => 'атам', 'word' => 'ата'),
    array('rus' => 'наш дедушка', 'type' => ';possessive_p_1;', 'wordTransform' => 'атамыз', 'word' => 'ата'),
    array('rus' => 'мой дом', 'type' => ';possessive_s_1;', 'wordTransform' => 'үйiм', 'word' => 'үй'),
    array('rus' => 'наш дом', 'type' => ';possessive_p_1;', 'wordTransform' => 'үйiмiз', 'word' => 'үй'),
    array('rus' => 'моя картина', 'type' => ';possessive_s_1;', 'wordTransform' => 'суретiм', 'word' => 'сурет'),
    array('rus' => 'наша картина', 'type' => ';possessive_p_1;', 'wordTransform' => 'суретiмiз', 'word' => 'сурет'),
    array('rus' => 'моя дочь', 'type' => ';possessive_s_1;', 'wordTransform' => 'қызым', 'word' => 'қыз'),
    array('rus' => 'наша дочь', 'type' => ';possessive_p_1;', 'wordTransform' => 'қызымыз', 'word' => 'қыз'),
    array('rus' => 'мой отец', 'type' => ';possessive_s_1;', 'wordTransform' => 'әкем', 'word' => 'әке'),
    array('rus' => 'наш отец', 'type' => ';possessive_p_1;', 'wordTransform' => 'әкемiз', 'word' => 'әке'),
    array('rus' => 'моя книга','type' => ';possessive_s_1;','wordTransform' => 'кiтабым','word' => 'кiтап'),
    array('rus' => 'наша книга', 'type' => ';possessive_p_1;', 'wordTransform' => 'кiтабымыз', 'word' => 'кiтап'),
    array('rus' => 'моё платье', 'type' => ';possessive_s_1;', 'wordTransform' => 'көйлегiм', 'word' => 'көйлек'),
    array('rus' => 'наше платье', 'type' => ';possessive_p_1;', 'wordTransform' => 'көйлегiмiз', 'word' => 'көйлек'),
    array('rus' => 'моя подушка', 'type' => ';possessive_s_1;', 'wordTransform' => 'жастығым', 'word' => 'жастық'),
    array('rus' => 'наша подушка ', 'type' => ';possessive_p_1;', 'wordTransform' => 'жастығымыз', 'word' => 'жастық'),
    array('rus' => 'твоя бабушка', 'type' => ';possessive_s_2;', 'wordTransform' => 'әжең', 'word' => 'әже'),
    array('rus' => 'твой ребёнок', 'type' => ';possessive_s_2;', 'wordTransform' => 'балаң', 'word' => 'бала'),
    array('rus' => 'твоё зеркало', 'type' => ';possessive_s_2;', 'wordTransform' => 'айнаң', 'word' => 'айна'),
    array('rus' => 'твоя полка', 'type' => ';possessive_s_2;', 'wordTransform' => 'сөрең', 'word' => 'сөре'),
    array('rus' => 'твоя комната', 'type' => ';possessive_s_2;', 'wordTransform' => 'бөлмең', 'word' => 'бөлме'),
    array('rus' => 'твой город', 'type' => ';possessive_s_2;', 'wordTransform' => 'қалаң', 'word' => 'қала'),
    array('rus' => 'твой друг', 'type' => ';possessive_s_2;', 'wordTransform' => 'досың', 'word' => 'дос'),
    array('rus' => 'твоя бумага', 'type' => ';possessive_s_2;', 'wordTransform' => 'қағазың', 'word' => 'қағаз'),
    array('rus' => 'твои часы', 'type' => ';possessive_s_2;', 'wordTransform' => 'сағатың', 'word' => 'сағат'),
    array('rus' => 'твой стол', 'type' => ';possessive_s_2;', 'wordTransform' => 'үстелiң', 'word' => 'үстел'),
    array('rus' => 'твой словарь', 'type' => ';possessive_s_2;', 'wordTransform' => 'сөздiгiң', 'word' => 'сөздiг'),
    array('rus' => 'твоя квартира', 'type' => ';possessive_s_2;', 'wordTransform' => 'пәтерiң', 'word' => 'пәтер'),
    array('rus' => 'Ваш город', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'қалаңыз', 'word' => 'қала'),
    array('rus' => 'Ваш ребёнок', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'балаңыз', 'word' => 'бала'),
    array('rus' => 'Ваше зеркало', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'айнаңыз', 'word' => 'айна'),
    array('rus' => 'Ваша полка', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'сөреңiз', 'word' => 'сөре'),
    array('rus' => 'Ваша комната', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'бөлмеңiз', 'word' => 'бөлме'),
    array('rus' => 'Ваша бабушка', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'әжеңiз', 'word' => 'әже'),
    array('rus' => 'Ваш друг', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'досыңыз', 'word' => 'дос'),
    array('rus' => 'Ваша бумага', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'қағазыңыз', 'word' => 'қағаз'),
    array('rus' => 'Ваши часы', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'сағатыңыз', 'word' => 'сағат'),
    array('rus' => 'Ваш стол', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'үстелiңiз', 'word' => 'үстел'),
    array('rus' => 'Ваш словарь', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'сөздiгiңiз', 'word' => 'сөздiг'),
    array('rus' => 'Ваша квартира', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'пәтерiңiз', 'word' => 'пәтер'),
    array('rus' => 'твой дедушка', 'type' => ';possessive_s_2;', 'wordTransform' => 'атаң', 'word' => 'ата'),
    array('rus' => 'Ваш дедушка', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'атаңыз', 'word' => 'ата'),
    array('rus' => 'твой дом', 'type' => ';possessive_s_2;', 'wordTransform' => 'үйiң', 'word' => 'үй'),
    array('rus' => 'Ваш дом', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'үйiңiз', 'word' => 'үй'),
    array('rus' => 'твоя картина', 'type' => ';possessive_s_2;', 'wordTransform' => 'суретiң', 'word' => 'сурет'),
    array('rus' => 'Ваша картина', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'суретiңiз', 'word' => 'сурет'),
    array('rus' => 'твоя дочь', 'type' => ';possessive_s_2;', 'wordTransform' => 'қызың', 'word' => 'қыз'),
    array('rus' => 'Ваша дочь', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'қызыңыз', 'word' => 'қыз'),
    array('rus' => 'твой отец', 'type' => ';possessive_s_2;', 'wordTransform' => 'әкең', 'word' => 'әке'),
    array('rus' => 'Ваш отец', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'әкеңiз', 'word' => 'әке'),
    array('rus' => 'твоя книга', 'type' => ';possessive_s_2;', 'wordTransform' => 'кiтабың', 'word' => 'кiтап'),
    array('rus' => 'Ваша книга', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'кiтабыңыз', 'word' => 'кiтап'),
    array('rus' => 'твоё платье', 'type' => ';possessive_s_2;', 'wordTransform' => 'көйлегiң', 'word' => 'көйлег'),
    array('rus' => 'Ваше платье', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'көйлегiңiз', 'word' => 'көйлег'),
    array('rus' => 'твоя подушка', 'type' => ';possessive_s_2;', 'wordTransform' => 'жастығың', 'word' => 'жастығ'),
    array('rus' => 'Ваша подушка', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'жастығыңыз', 'word' => 'жастығ'),
    array('rus' => 'его город', 'type' => ';possessive_s_3;', 'wordTransform' => 'қаласы', 'word' => 'қала'),
    array('rus' => 'её ребёнок', 'type' => ';possessive_s_3;', 'wordTransform' => 'баласы', 'word' => 'бала'),
    array('rus' => 'его зеркало', 'type' => ';possessive_s_3;', 'wordTransform' => 'айнасы', 'word' => 'айна'),
    array('rus' => 'её полка', 'type' => ';possessive_s_3;', 'wordTransform' => 'сөресi', 'word' => 'сөре'),
    array('rus' => 'его комната', 'type' => ';possessive_s_3;', 'wordTransform' => 'бөлмесi', 'word' => 'бөлме'),
    array('rus' => 'их бабушка', 'type' => ';possessive_p_3;', 'wordTransform' => 'әжесi', 'word' => 'әже'),
    array('rus' => 'его друг', 'type' => ';possessive_s_3;', 'wordTransform' => 'досы', 'word' => 'дос'),
    array('rus' => 'её бумага', 'type' => ';possessive_s_3;', 'wordTransform' => 'қағазы', 'word' => 'қағаз'),
    array('rus' => 'их часы', 'type' => ';possessive_p_3;', 'wordTransform' => 'сағаты', 'word' => 'сағат'),
    array('rus' => 'их стол', 'type' => ';possessive_p_3;', 'wordTransform' => 'үстелi', 'word' => 'үстел'),
    array('rus' => 'его словарь', 'type' => ';possessive_s_3;', 'wordTransform' => 'сөздiгi', 'word' => 'сөздiг'),
    array('rus' => 'её квартира', 'type' => ';possessive_s_3;', 'wordTransform' => 'пәтерi', 'word' => 'пәтер'),
    array('rus' => 'наш разговор', 'type' => ';possessive_p_1;', 'wordTransform' => 'сөйлесуiмiз', 'word' => 'сөйлесу' ),
    array('rus' => 'мои занятия', 'type' => ';possessive_s_1;', 'wordTransform' => 'оқуым', 'word' => 'оқу'),
    array('rus' => 'его выбор', 'type' => ';possessive_s_3;', 'wordTransform' => 'таңдауы', 'word' => 'таңдау'),
    array('rus' => 'ваша встреча', 'type' => ';possessive_s_2_r;', 'wordTransform' => 'кездесуiңiз', 'word' => 'кездесу'),
);

$test123Array = array(
    array('rus' => 'в наших городах', 'type' => ';plural;possessive_p_1;mestniy;', 'wordTransform' => 'қалаларымызда', 'word' => 'қала'),
    array('rus' => 'на твоих бабочках', 'type' => ';plural;possessive_s_2;mestniy;', 'wordTransform' => 'көбелектерiңде', 'word' => 'көбелек'),
    array('rus' => 'из наших городов', 'type' => ';plural;possessive_p_1;ishodniy;', 'wordTransform' => 'қалаларымыздан', 'word' => 'қала'),
    array('rus' => 'в наши города', 'type' => ';plural;possessive_p_1;datelniy;', 'wordTransform' => 'қалаларымызға', 'word' => 'қала'),
    array('rus' => '', 'type' => ';plural;possessive_p_2_r;mestniy;', 'wordTransform' => 'әкелерiңiзде', 'word' => 'әке'),
    array('rus' => '', 'type' => ';plural;possessive_p_2_r;datelniy;', 'wordTransform' => 'әкелерiңiзге', 'word' => 'әке'),
    array('rus' => '', 'type' => ';plural;possessive_p_2;datelniy;', 'wordTransform' => 'достарыңа', 'word' => 'дос'),
    array('rus' => '', 'type' => ';plural;possessive_p_2_r;ishodniy;', 'wordTransform' => 'достарыңыздан', 'word' => 'дос'),
    array('rus' => '', 'type' => ';plural;possessive_p_3;ishodniy;', 'wordTransform' => 'әкелерiнен', 'word' => 'әке'),
    array('rus' => '', 'type' => '', 'wordTransform' => '', 'word' => ''),
    array('rus' => '', 'type' => '', 'wordTransform' => '', 'word' => ''),

);

$testArray = $test123Array;

echo '<table border="1">
<tr>
 <td><b>Исходное слово</b></td>
 <td><b>Конфигурация слово образования</b></td>
 <td><b>Слово выданное программой</b></td>
 <td><b>Слово тест</b></td>
 <td><b>Слово тест - русский</b></td>
 <td><b>Верность</b></td>
</tr>';

foreach ($testArray as $faceArr) {
$kaz = new Kaz($faceArr['word']);
$resultArray = $kaz->getFlectiveClass();

echo '<tr>
 <td>' . $faceArr['word'] . '</td>';
if(empty($resultArray[$faceArr['type']]['word'])) {
 echo '<td>' . $faceArr['type'] . '</td>';
 echo '<td> нет результата </td>';
} else {
 echo '<td>' . $resultArray[$faceArr['type']]['type'] . '</td>';
 echo '<td>' . $resultArray[$faceArr['type']]['word'] . '</td>';
}

echo '<td>' . $faceArr['wordTransform'] . '</td>
 <td>' . $faceArr['rus'] . '</td>';
if( !empty($resultArray[$faceArr['type']]['word'])) {
 if (preg_replace('/\s+/', '', $resultArray[$faceArr['type']]['word']) === preg_replace('/\s+/', '', $faceArr['wordTransform'])) {
 echo '<td>Верно</td>';
 } else {
 echo '<td>Неверно</td>';
 }
} else {
 echo '<td>Неверно</td>';
}

echo '</tr>';

if (!empty($resultArray[$faceArr['type']])) {

}
}

echo '</table>';

function recursive_array_search($needle, $haystack)
{
foreach ($haystack as $key => $value) {
 $current_key = $key;
 if ($needle === $value OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
 return $current_key;
 }
}
return false;
}


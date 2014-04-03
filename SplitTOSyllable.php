<?php
/**
 * Created by PhpStorm.
 * User: salerat
 * Date: 4/3/14
 * Time: 4:04 PM
 */

namespace kaz;
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

ini_set( 'default_charset', 'UTF-8' );
mb_internal_encoding("UTF-8");

class SplitTOSyllable
{

    protected $vowel = 'аәоөұүыiеиуёэюя'; // Гласные буквы
    protected $voiced = 'ғңбвгджзрлмншщ'; // Звонкие и шипящие согласные
    protected $deaf = 'қһкпстфхцч'; // Глухие согласные
    // var brief  = new String ('й';                                      // Й
    protected $other = 'ьъ'; // Другие
    protected $cons = 'ғқңһбвгджзкпстфхцчшщйрлмн'; // Все согласные */
/*

    protected $vowel;
    protected $voiced;
    protected $deaf;
    // var brief  = new String ('й';                                      // Й
    protected $other;
    protected $cons;
*/
    function __construct() {
     /*   $this->vowel = iconv("UTF-8", "PT154", 'аәоөұүыiеиуёэюя');
        $this->voiced =iconv("UTF-8", "PT154", 'ғңбвгджзрлмншщ'); // Звонкие и шипящие согласные
        $this->deaf = iconv("UTF-8", "PT154", 'қһкпстфхцч'); // Глухие согласные
        // var brief  = new String ('й');                                      // Й
        $this->other = iconv("UTF-8", "PT154", 'ьъ'); // Другие
        $this->cons = iconv("UTF-8", "PT154", 'ғқңһбвгджзкпстфхцчшщйрлмн'); // Все согласные */
    }

    // Валидация правильности введенной строки
    protected function validateString($s)
    {
        // Поленился делать :)
        return $s;
    }

    protected function mbStringToArray ($string) {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string,0,1,"UTF-8");
            $string = mb_substr($string,1,$strlen,"UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

// Есть ли в строке гласные?
    protected function isNotLastSep($remainStr)
    {
        $vowelsArray = $this->mbStringToArray($this->vowel);
        foreach($vowelsArray as $a) {
            if (mb_stripos($remainStr,$a) !== false) return true;
        }
        return false;
    }

    // Добавляем слог в массив и начинаем $новый слог
    protected function addSep(&$sepArr, &$tmpS)
    {
        array_push($sepArr, $tmpS);
        $tmpS = '';
    }

// Собственно функция разбиения слова на слоги
    public function getSeparatedString($s)
    {
        $s = $this->validateString($s);
        $tmpL = ''; // Текущий символ
        $tmpS = ''; // Текущий слог
        $sepArr = array(); // Массив слогов
        for ($i = 0; $i < mb_strlen($s); $i++) {
            $tmpL = mb_substr($s, $i, 1);
            $tmpS.= $tmpL;

            // Проверка на признаки конца слогов

            // если текущая гласная и следующая тоже гласная
            if (
                ($i < mb_strlen($s) - 1) &&
                (mb_strpos($this->vowel, $tmpL) !== false) &&
                (mb_strpos($this->vowel, mb_substr($s, $i + 1, 1)) !== false)
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }
            // если текущая гласная, следующая согласная, а после неё гласная
            if (
                ($i < mb_strlen($s) - 2) &&
                (mb_strpos($this->vowel, $tmpL) !== false) &&
                (mb_strpos($this->cons, mb_substr($s, $i + 1, 1)) !== false) &&
                (mb_strpos($this->vowel, mb_substr($s, $i + 2, 1)) !== false)
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }
            // если текущая гласная, следующая глухая согласная, а после согласная и это не последний слог
            if (
                ($i < mb_strlen($s) - 2) &&
                (mb_strpos($this->vowel, $tmpL) !== false) &&
                (mb_strpos($this->deaf, mb_substr($s, $i + 1, 1)) !== false) &&
                (mb_strpos($this->cons, mb_substr($s, $i + 2, 1)) !== false) &&
                ($this->isNotLastSep(mb_substr($s, $i + 1, mb_strlen($s) - $i + 1)))
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }
            // если текущая звонкая или шипящая согласная, перед ней гласная, следующая не гласная и не другая, и это не последний слог
            if (
                ($i > 0) &&
                ($i < mb_strlen($s) - 1) &&
                (mb_strpos($this->voiced, $tmpL) !== false) &&
                (mb_strpos($this->vowel, mb_substr($s, $i - 1, 1)) !== false) &&
                (mb_strpos($this->vowel, mb_substr($s, $i + 1, 1)) === false) &&
                (mb_strpos($this->other, mb_substr($s, $i + 1, 1)) === false) &&
                ($this->isNotLastSep(mb_substr($s, $i + 1, mb_strlen($s) - $i + 1)))
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }
            // если текущая другая, а следующая не гласная если это первый слог
            if (
                ($i < mb_strlen($s) - 1) &&
                (mb_strpos($this->other, $tmpL) !== false) &&
                ((mb_strpos($this->vowel, mb_substr($s, $i + 1, 1)) === false) ||
                    ($this->isNotLastSep(mb_substr($s, 0, $i))))
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }
        }

        array_push($sepArr, $tmpS);
        die(var_dump($sepArr));
        //die(var_dump(iconv("PT154", "UTF-8", $sepArr[0])));
        //return $tmpS;
    }

}

$t = new SplitTOSyllable();
$text = 'емтихандар';

//$text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
//$tmpL = mb_substr($text, 0, 1);

//echo $text;
//$text = iconv("UTF-8", "PT154", $text);
$c = $t->getSeparatedString($text);

echo '
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Test</title>

    </head>
<body>

'.$c.'

</body>
</html>
';

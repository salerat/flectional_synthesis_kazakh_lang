<?php
namespace kaz;

class SplitToSyllable
{
    protected $word;
    protected $syllableArray;

    protected $vowel = 'аәоөұүыiеиуёэюя'; // Гласные буквы
    protected $voiced = 'ғңбвгджзрлмншщ'; // Звонкие и шипящие согласные
    protected $deaf = 'қһкпстфхцч'; // Глухие согласные                                    // Й
    protected $cons = 'ғқңһбвгджзкпстфхцчшщйрлмн'; // Все согласные */

    function __construct($word)
    {
        ini_set('default_charset', 'UTF-8');
        mb_internal_encoding("UTF-8");
        $this->word = $word;
        $this->syllableArray = $this->getSeparatedString($this->word);
    }

    // Валидация правильности введенной строки
    protected function validateString($s)
    {
        return $s;
    }

    protected function mbStringToArray($string)
    {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

// Есть ли в строке гласные?
    protected function isNotLastSep($remainStr)
    {
        $vowelsArray = $this->mbStringToArray($this->vowel);
        foreach ($vowelsArray as $a) {
            if (mb_stripos($remainStr, $a) !== false) {
                return true;
            }
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
    protected function getSeparatedString($s)
    {
        $s = $this->validateString($s);
        $tmpL = ''; // Текущий символ
        $tmpS = ''; // Текущий слог
        $sepArr = array(); // Массив слогов
        for ($i = 0; $i < mb_strlen($s); $i++) {
            $tmpL = mb_substr($s, $i, 1);
            $tmpS .= $tmpL;

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
                ($this->isNotLastSep(mb_substr($s, $i + 1, mb_strlen($s) - $i + 1)))
            ) {
                $this->addSep($sepArr, $tmpS);
                continue;
            }

        }

        array_push($sepArr, $tmpS);
        return $sepArr;
    }

    public function getSyllableArray()
    {
        return $this->syllableArray;
    }

    public function getLastSyllable()
    {
        return end($this->syllableArray);
    }

}

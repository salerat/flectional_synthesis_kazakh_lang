<?php

namespace kaz;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

class Kaz
{

    protected $mysqli;
    protected $wordOriginal;

    function getLastSyllable()
    {

    }

//возвращает 0 если следущая буква гласная
//возвращает 1 если текущая буква согласная и последняя в слове
//возвращает 2 если текущая буква препдпоследняя согласная, с условием того, что после следущей согласной буквы следует ГЛАСНАЯ
//возвращает 3 если текущая буква препдпоследняя согласная, с условием того, что после следущей согласной буквы следует СОГЛАСНАЯ
//возвращает 3 если текущая буква препдпоследняя согласная, с условием того, что после следущей согласной больше БУКВ нет
    protected function controlConsonantStartedByConsonant($consonant, $splitedWord, $starNumber)
    {
        if (!empty($splitedWord[$starNumber + 1])) {
            if (!in_array($splitedWord[$starNumber + 1], $consonant)) {
                return 0;
            } else {
                if (!empty($splitedWord[$starNumber + 2])) {
                    if (!in_array($splitedWord[$starNumber + 2], $consonant)) {
                        return 2;
                    } else {
                        return 3;
                    }
                } else {
                    return 4;
                }
            }
        } else {
            return 1;
        }
    }

    public function breakToSyllable()
    {

        $result = $this->mysqli->query('SELECT word FROM vowel');
        $vowels = array();
        while ($row = $result->fetch_assoc()) {
            $vowels[] = $row['word'];
        }
        $result = $this->mysqli->query('SELECT word FROM consonant');
        $consonant = array();
        while ($row = $result->fetch_assoc()) {
            $consonant[] = $row['word'];
        }
        $splitedWord = str_split($this->wordOriginal);

        $syllableArray = array();
        $startedByConsonant = false;
        $inSyllable = false;
        $count = 0;
        foreach ($splitedWord as $key => $word) {
            if (!$inSyllable) {
                if (in_array($word, $consonant)) {
                    $startedByConsonant = true;
                } else {
                    $startedByConsonant = false;
                }
                $count++;
                $inSyllable = true;
                $syllableArray[$count] = '';
            }

            $syllableArray[$count] = $syllableArray[$count] . $word;

            if (($startedByConsonant)) {
                if (in_array($word, $consonant)) {
                    $resNumber = $this->controlConsonantStartedByConsonant($consonant, $splitedWord, $key);
                }
            } else {

            }

        }
        die(var_dump($consonant));
    }

    function __construct($wordOriginal)
    {
        $this->mysqli = new \mysqli("localhost", "root", "gerogle", "term_k");
        $this->mysqli->set_charset('utf8');
        $this->wordOriginal = $wordOriginal;
    }

    public function test()
    {
        $query = "SELECT * FROM word_case";
        $result = $this->mysqli->query($query);
        while ($row = $result->fetch_assoc()) {
            echo($row['case']);
        }
    }

    public function generateEndings()
    {
        $word = "адам";
        $query = "SELECT * FROM plural, possessive,case_letter";
        $result = $this->mysqli->query($query);
        while ($row = $result->fetch_assoc()) {
            echo($word . $row['plural'] . $row['possessive'] . $row['case_letter'] . PHP_EOL);
        }
    }
}

$kaz = new Kaz('адам');
$kaz->breakToSyllable();


/*
 *
Исходный падеж
Шығыс септiк
(кiмнен?, неден?, қайдан?)
дан / ден	 гласн / л р у й / ж з
тан / тен	 глух / б в г д
нан / нен	 м н ң / 3


Местный падеж
Жатыс септiк
(кiмде?, неде?, қайда?, қашан?)
да / де	 гласн / сонор / ж з
та / те	 глух / б в г д
нда / нде	3


Дательно-направит. падеж
Барыс септiк
(кiмге?, неге?, қайда?)
ға / ге	 гласн / сонор / ж з
қа / ке	 глух / б в г д
на / не	3
а / е	1 / 2


Родительный падеж
Iлiк септiк
(кiмнiң?, ненiң?)
дың / дiң	 л р у й / ж з
тың / тiң	 глух / б в г д
ның / нiң	 гласн / м н ң / 3


Винительный падеж
Табыс септiк
(кiмдi?, ненi?)
ды / дi    	 сонор / ж з
ты / тi	 глух / б в г д
ны / нi	 гласн
н	3


Притяжательные окончания
2-ая форма
(кiмдiкi?, ненiкi?)
дiкi    	 сонор / ж з
тiкi	 глух / б в г д
нiкi	 гласн


Множественное число
дар / дер	 л м н ң / ж з
тар / тер	 глух / б в г д
лар / лер	 гласн / р у й


Отрицание, вопрос
ба / бе    	 м н ң / ж з
па / пе	 глух / б в г д
ма / ме	 гласн / л р у й


Творительный падеж
Көмектес септiк
(кiммен?, немен?)
бен        	 ж з
пен	 глух / б в г д
мен	 гласн / сонор / 3
 */
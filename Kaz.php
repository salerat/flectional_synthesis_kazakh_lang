<?php

namespace kaz;
require 'SplitToSyllable.php';

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

class Kaz
{
    protected $mysqli;
    protected $wordOriginal;
    protected $lastSyllableArray;
    protected $flectiveClassArray;

    protected $vowel; // Гласные буквы
    protected $vowelHard; // Гласные буквы твердые
    protected $vowelSoft; // Гласные буквы мягкие
    protected $voiced; // Звонкие и шипящие согласные
    protected $deaf; // Глухие согласные
    protected $cons; // Все согласные

    function __construct($wordOriginal)
    {
        $this->mysqli = new \mysqli("localhost", "root", "gerogle", "term_k");
        $this->mysqli->set_charset('utf8');
        $this->wordOriginal = $wordOriginal;

        $query = "SELECT word FROM vowel";
        $result = $this->mysqli->query($query);
        $this->vowel = array();
        while ($row = $result->fetch_assoc()) array_push($this->vowel, $row['word']);

        $query = "SELECT word FROM vowel WHERE hard = 1";
        $result = $this->mysqli->query($query);
        $this->vowelHard = array();
        while ($row = $result->fetch_assoc()) array_push($this->vowelHard, $row['word']);

        $query = "SELECT word FROM vowel WHERE soft = 1";
        $result = $this->mysqli->query($query);
        $this->vowelSoft = array();
        while ($row = $result->fetch_assoc()) array_push($this->vowelSoft, $row['word']);

        $query = "SELECT word FROM consonant WHERE voiced = 1 OR hiss = 1";
        $result = $this->mysqli->query($query);
        $this->voiced = array();
        while ($row = $result->fetch_assoc()) array_push($this->voiced, $row['word']);

        $query = "SELECT word FROM consonant WHERE deaf = 1";
        $result = $this->mysqli->query($query);
        $this->deaf = array();
        while ($row = $result->fetch_assoc()) array_push($this->deaf, $row['word']);

        $query = "SELECT word FROM consonant";
        $result = $this->mysqli->query($query);
        $this->cons = array();
        while ($row = $result->fetch_assoc()) array_push($this->cons, $row['word']);

        $query = "SELECT * FROM rule_type WHERE flective = 1";
        $result = $this->mysqli->query($query);
        $this->flectiveClassArray = array();
        while ($row = $result->fetch_assoc()) array_push($this->flectiveClassArray, $row);

    }
    // определяем мягкость - твердость
    // 1 - возвращает если в слове гласная твердая
    // 0 - возвращает если в слове гласная мягкая
    protected function detectHardSoftEnding() {
        $resultWord = '';
        foreach(array_reverse($this->lastSyllableArray) as $word) {
            if( mb_strpos( implode($this->vowel), $word) !== false ) {
                $resultWord = $word;
                break;
            }
        }
        if( !empty($resultWord) ) {
            if( in_array( $resultWord, $this->vowelHard, true) !== false ) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return false;
        }
    }

    protected function detectFlectiveClass() {
        $lastWord = end($this->lastSyllableArray);
        foreach( $this->flectiveClassArray as $flectiveClass) {
            if($flectiveClass['type'] == 'word') {
                if( mb_strpos( $flectiveClass['word'], $lastWord) !== false ) {
                    return $flectiveClass['id'];
                }
            } elseif($flectiveClass['type'] == 'deaf') {
                foreach( $this->deaf as $deafWord) {
                    if( in_array($deafWord, $flectiveClass['word'], true) !== false ) {
                        return $flectiveClass['id'];
                    }
                }
            } elseif($flectiveClass['type'] == 'vowel') {
                foreach( $this->vowel as $vowelWord) {
                    if( in_array($vowelWord, $flectiveClass['word'], true) !== false ) {
                        return $flectiveClass['id'];
                    }
                }
            }
        }
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

    /*
     * Варианты окончаний
     * 1
     * 2
     * 3
     * 123
     * 23
     * 13
     * */

    public function generateAllFlectiveClasses() {
        foreach( $this->flectiveClassArray as $flectiveClass) {
            //заполнили мн число и падежы
         /*   $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 3);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 3);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 1);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 1);*/
            //заполнили все лица, кроме второй формы
          //  $this->generateSyllableForFace($flectiveClass, 0, 1, '');
          //  $this->generateSyllableForFace($flectiveClass, 1, 0, '');

            //заполненые лица второй формы
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 2, "case_type_name ='possessive2' AND ");
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 2, "case_type_name ='possessive2' AND ");
        }
    }

    //не забыть про исключение "у" флективного класса 5
    /* 11 - если слово оканчивается на согласную, и слово твердое
     * 12 - если слово оканчивается на согласную, и слово мягкое
     * 13 - если слово оканчивается на согласную, и правил нет
     * */
    protected function generateSyllableForFace($flectiveClass, $hard, $soft, $lastSyllableArray) {
        $hardSoftStr = '';
        if($hard == 0) {
            $hardSoftStr = 'soft=1';
            $additionalStr = 'ы';
        } else {
            $hardSoftStr = 'hard=1';
            $additionalStr = 'i';
        }
        if( (($flectiveClass['type'] == 'word') && (mb_strpos( $flectiveClass['word'], 'у') === false )) || (($flectiveClass['type'] == 'deaf')) ) {
            $additionalQueryString = "rule LIKE '%;11;%' OR rule LIKE '%;12;%'";
        } else {
            $additionalQueryString = "rule LIKE '%;".$flectiveClass['id'].";%' OR rule LIKE '%;13;%'";
            $additionalStr='';
        }
        $query = "SELECT * FROM word_case WHERE ".$hardSoftStr." AND case_position = 2 AND case_type_name <> 'possessive2' AND (".$additionalQueryString.");";
        $result = $this->mysqli->query($query);

        if(!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                $query2 = "INSERT INTO all_case (flective_id,word,type,hard,soft) VALUES (".$flectiveClass['id'].",'".$additionalStr.$row['case_letter']."',';".$row['case_type_name'].";', ".$hard.", ".$soft.");";
                $result2 = $this->mysqli->query($query2);
                //  echo $this->mysqli->error;
            }
        }
    }

    protected function generateFlectiveClassForOneType($flectiveId, $hard, $soft, $casePosition, $additionalQuery = '') {
        $hardSoftStr = '';
        if($hard == 0) {
            $hardSoftStr = 'soft=1';
        } else {
            $hardSoftStr = 'hard=1';
        }
        $query = "SELECT * FROM word_case WHERE ".$additionalQuery."case_position=".$casePosition." AND ".$hardSoftStr." AND (rule LIKE '%;".$flectiveId.";%' OR rule LIKE '%,".$flectiveId.";%' OR rule LIKE '%;".$flectiveId.",%')";
        $result = $this->mysqli->query($query);
    //    echo $this->mysqli->error;
    //    die(var_dump(123));
        if(!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                $query2 = "INSERT INTO all_case (flective_id,word,type,hard,soft) VALUES (".$flectiveId.",'".$row['case_letter']."',';".$row['case_type_name'].";', ".$hard.", ".$soft.");";
                $result2 = $this->mysqli->query($query2);
              //  echo $this->mysqli->error;
            }
        }
    }

    public function getFlectiveClass() {
        $splitSyllable = new SplitToSyllable($this->wordOriginal, implode($this->vowel), implode($this->voiced), implode($this->deaf), implode($this->cons) );
        $this->lastSyllableArray = $splitSyllable->mbStringToArray($splitSyllable->getLastSyllable());

        //echo $this->wordOriginal;
        //echo $this->detectHardSoftEnding();
        //echo $this->detectFlectiveClass();
        echo $this->generateAllFlectiveClasses();

    }
}

$kaz = new Kaz('емтихан');
$kaz->getFlectiveClass();


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
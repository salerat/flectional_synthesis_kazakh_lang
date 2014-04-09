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
    protected $splitSyllable;
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

        $this->splitSyllable = new SplitToSyllable($this->wordOriginal, implode($this->vowel), implode($this->voiced), implode($this->deaf), implode($this->cons) );
        $this->lastSyllableArray = $this->splitSyllable->mbStringToArray($this->splitSyllable->getLastSyllable());

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

    protected function detectFlectiveClass($lastSyllableArray) {
        $lastWord = end($lastSyllableArray);
        foreach( $this->flectiveClassArray as $flectiveClass) {
            if($flectiveClass['type'] == 'word') {
                if( mb_strpos( $flectiveClass['word'], $lastWord) !== false ) {
                    return $flectiveClass;
                }
            } elseif($flectiveClass['type'] == 'deaf') {
                    if( in_array($lastWord, $this->deaf, true) !== false ) {
                        return $flectiveClass;
                    }
            } elseif($flectiveClass['type'] == 'vowel') {
                    if( in_array($lastWord, $this->vowel, true) !== false ) {
                        return $flectiveClass;
                    }
            }
        }
        return false;
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
     * 1 мн число и вопрос +
     * 2 притяжательные +
     * 3 падежи +
     * 123
     * 23 +
     * 13 +
     * 12 +
     * */

    public function generateAllFlectiveClasses() {
        echo 'Generate 1,2,3'.PHP_EOL;
        foreach( $this->flectiveClassArray as $flectiveClass) {
            //заполнили мн число и падежы
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 3);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 3);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 1);
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 1);

            //заполнили все лица, кроме второй формы
            $this->generateSyllableForFace($flectiveClass, 0, 1, 2);
            $this->generateSyllableForFace($flectiveClass, 1, 0, 2);

            //заполненые лица второй формы
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 1, 0, 2, "case_type_name ='possessive2' AND ");
            $this->generateFlectiveClassForOneType($flectiveClass['id'], 0, 1, 2, "case_type_name ='possessive2' AND ");
        }
        echo 'Generate 1 + 3, 2 + 3'.PHP_EOL;
        //генерируем 1 + 3 и 2 + 3
        $this->generateSyllableForFirstAndThirdClass(1, 0, 1, 3);
        $this->generateSyllableForFirstAndThirdClass(0, 1, 1, 3);
        $this->generateSyllableForFirstAndThirdClass(1, 0, 2, 3);
        $this->generateSyllableForFirstAndThirdClass(0, 1, 2, 3);

        //генерируем 1 + 2 для личных второй формы
        $this->generateSyllableForFirstAndThirdClass(1, 0, 1, 2, "case_type_name = 'possessive2' AND ");
        $this->generateSyllableForFirstAndThirdClass(0, 1, 1, 2, "case_type_name = 'possessive2' AND ");

        echo 'Generate 1 + 2'.PHP_EOL;
        //генерируем 1 + 2
        $this->generateSyllableForFirstAndSecondClass(0, 1, 1, 2);
        $this->generateSyllableForFirstAndSecondClass(1, 0, 1, 2);

        echo 'Generate 12 + 3'.PHP_EOL;
        //генерируем 12 + 3
        $this->generateSyllableForFirstAndThirdClass(0, 1, 12, 3);
        $this->generateSyllableForFirstAndThirdClass(1, 0, 12, 3);
    }

    //генерируем по началу 1 + 3 и 2 + 3
    protected function generateSyllableForFirstAndThirdClass($hard, $soft, $caseNumber1, $caseNumber2, $additionalQuery='') {
        $hardSoftStr = '';
        if($hard == 0) {
            $hardSoftStr = 'soft=1';
        } else {
            $hardSoftStr = 'hard=1';
        }
        $query = "SELECT * FROM all_case WHERE type_format=".$caseNumber1." AND ".$hardSoftStr;
        $resultQuestion = $this->mysqli->query($query);

        while ($rowQuestion = $resultQuestion->fetch_assoc()) {
            if( !empty($rowQuestion['face']) ) {
                $faceIdQuery='SELECT id FROM rule_type WHERE face='.$rowQuestion['face'];
                $result3 = $this->mysqli->query($faceIdQuery);
                $ruleNumber=0;
                while ($row = $result3->fetch_assoc()) {
                    $ruleNumber = $row['id'];
                    break;
                }
                $additionalQueryIfFace = "AND (rule LIKE '%;".$ruleNumber.";%')";
            } else {
                if($caseNumber1==12) {
                    $syllableArray = $this->splitSyllable->mbStringToArray($rowQuestion['last_case']);
                } else {
                    $syllableArray = $this->splitSyllable->mbStringToArray($rowQuestion['word']);
                }
                $flectiveId = $this->detectFlectiveClass($syllableArray)['id'];
                $additionalQueryIfFace = "AND (rule LIKE '%;".$flectiveId.";%' OR rule LIKE '%,".$flectiveId.";%' OR rule LIKE '%;".$flectiveId.",%')";
            }


            $query = "SELECT * FROM word_case WHERE ".$additionalQuery."case_position=".$caseNumber2." AND ".$hardSoftStr." ".$additionalQueryIfFace;
            $resultCase = $this->mysqli->query($query);
            while ($rowCase = $resultCase->fetch_assoc()) {
                $wordType = $rowQuestion['type'].$rowCase['case_type_name'].';';
                $query2 = "INSERT INTO all_case (flective_id,word,type,hard,soft, type_format) VALUES (".$flectiveId.",'".$rowQuestion['word'].$rowCase['case_letter']."','".$wordType."', ".$hard.", ".$soft.", ".strval($caseNumber1).strval($caseNumber2).");";
                $result2 = $this->mysqli->query($query2);
            }
            //die(var_dump($flectiveId, $resultCase->num_rows));
        }
    }

    //генерируем под 1 + 2
    protected function generateSyllableForFirstAndSecondClass($hard, $soft, $caseNumber1, $caseNumber2, $additionalQuery='') {
        $hardSoftStr = '';
        if($hard == 0) {
            $hardSoftStr = 'soft=1';
        } else {
            $hardSoftStr = 'hard=1';
        }
        $query = "SELECT * FROM all_case WHERE type_format=".$caseNumber1." AND ".$hardSoftStr;
        $resultQuestion = $this->mysqli->query($query);
        while ($rowQuestion = $resultQuestion->fetch_assoc()) {
            $syllableArray = $this->splitSyllable->mbStringToArray($rowQuestion['word']);
            $flectiveClassCase = $this->detectFlectiveClass($syllableArray);
            $this->generateSyllableForFace($rowQuestion['flective_id'], $hard, $soft, $caseNumber2, intval(strval($caseNumber1).strval($caseNumber2)), $rowQuestion['word'], substr($rowQuestion['type'], 1), $flectiveClassCase) ;
        }
    }
    //не забыть про исключение "у" флективного класса 5
    /* 11 - если слово оканчивается на согласную, и слово твердое
     * 12 - если слово оканчивается на согласную, и слово мягкое
     * 13 - если слово оканчивается на согласную, и правил нет
     * */
    protected function generateSyllableForFace($flectiveClass, $hard, $soft, $casePosition, $caseTypeFormat = 2, $additionalCase = '', $additionalType = '', $flectiveClassCase = null) {

        if(!is_null($flectiveClassCase)) {
            $flectiveId=$flectiveClass;
            $flectiveClass=$flectiveClassCase;
        } else {
            $flectiveId=$flectiveClass['id'];
        }
        $hardSoftStr = '';
        if($hard == 0) {
            $hardSoftStr = 'soft=1';
            $additionalStr = 'ы';
        } else {
            $hardSoftStr = 'hard=1';
            $additionalStr = 'i';
        }
        if($flectiveClass['type'] == 'vowel') {
            $additionalStr='';
        }
        $query = "SELECT * FROM word_case WHERE ".$hardSoftStr." AND case_position = ".$casePosition." AND case_type_name <> 'possessive2' ;";
        $result = $this->mysqli->query($query);

        if(!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                if(mb_strpos( $row['rule'], strval(13)) !== false) {
                    $additionalStr='';
                }
                $query2 = "INSERT INTO all_case (flective_id,word,type,hard,soft, type_format, last_case, face) VALUES (".$flectiveId.",'".$additionalCase.$additionalStr.$row['case_letter']."',';".$additionalType.$row['case_type_name'].";', ".$hard.", ".$soft.", ".$caseTypeFormat.", '".$additionalStr.$row['case_letter']."', ".$row['face'].");";
                $result2 = $this->mysqli->query($query2);
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
                $query2 = "INSERT INTO all_case (flective_id,word,type,hard,soft, type_format) VALUES (".$flectiveId.",'".$row['case_letter']."',';".$row['case_type_name'].";', ".$hard.", ".$soft.", ".$casePosition.");";
                $result2 = $this->mysqli->query($query2);
              //  echo $this->mysqli->error;
            }
        }
    }

    public function getFlectiveClass() {

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
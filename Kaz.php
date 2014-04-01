<?php

namespace kaz;

error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');

class Kaz {

    protected $mysqli;

    function __construct()
    {
        $this->mysqli = new \mysqli("localhost", "root", "gerogle", "test");
        $this->mysqli->set_charset('utf8');
    }

    public function test()
    {
        $query = "SELECT * FROM word_case";
        $result=$this->mysqli->query($query);
        while ($row = $result->fetch_assoc()) {
            echo($row['case']);
        }
    }

    public function generateEndings() {
        $word = "адам";
        $query = "SELECT * FROM plural, possessive,case_letter";
        $result=$this->mysqli->query($query);
        while ($row = $result->fetch_assoc()) {
            echo($word.$row['plural'].$row['possessive'].$row['case_letter'].PHP_EOL);
        }
    }
}

$kaz = new Kaz();
$kaz->generateEndings();


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
<?php
include "debugpack.php";
DbgAll(1);

// сессия истекает через 2 часа, главное чтобы на презентации не вылетела сессия админа)
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();

function AdminAccess()
{
    return isset($_SESSION['admin']);
}


if (AdminAccess())
    DbgAll();
define("SITE_HTTP_ROOT", "http://qrbattle.2rom.ru/");

/** @global $db */
$db = new mysqli_dbg("localhost", "iokhtr_qrbattle", "ReFh<fnnkt1", "iokhtr_qrbattle");
$db->query("SET NAMES 'UTF8'");

define("CODE_WHITE", 1);
define("CODE_GREEN", 2);
define("CODE_BLUE", 3);
define("CODE_RED", 4);
define("CODE_HIDESCORE", 5);

define("MD5SALT", "bla blaq! bla qr 2017 codes");
date_default_timezone_set("Asia/Vladivostok");

$CODES = array(
    CODE_WHITE => array(
        'count' => 250,
        'name' => 'Черный код',
        'desc' => 'Черный код: +1 выбранной команде'
    ),
    CODE_GREEN => array(
        'count' => 15,
        'name' => 'Зелёный код',
        'desc' => 'Зелёный код: начисление баллов выбранной команде не более 3х раз'
    ),
    CODE_BLUE => array(
        'count' => 37,
        'name' => 'Синий код',
        'desc' => 'Синий код: +5 баллов за правильный ответ выбранной команде'
    ),
    CODE_RED => array(
        'count' => 10,
        'name' => 'Красный код',
        'desc' => 'Красный код: отнять баллы у выбранной команды'
    ),
    CODE_HIDESCORE => array(
        'count' => 15,
        'name' => 'Код, гуляющий сам по себе',
        'desc' => 'Жёлтый код: скрыть или показать баллы выбранной команды в таблице результатов'
    ),
);

// команды нужны во многих местах
// но они вне лок тейблс, как бы это пофиг
$res = $db->query("(SELECT * FROM teams WHERE hidetime=0 ORDER BY score DESC) UNION (SELECT * FROM teams WHERE hidetime!=0 ORDER BY tid)");
$teams = array();
while ($r = $res->fetch_assoc()) {
    $teams[$r['tid']] = $r;
}


header("Content-Type: text/html; charset=UTF-8");

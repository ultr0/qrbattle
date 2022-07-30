<?php

/*
cishost.ru
qrbattle
oD8w9pxx

ftp:
user70878
T9iP9y93

db:
user70878_db_qrbattle
user70878_qr
BRmLM5GW

*/

include "start.php";

global $db, $teams;


if (isset($_GET['q'])) {
    // тут лочим тейблу ивенты
//    $db->query("LOCK TABLES events WRITE, codes WRITE, quests WRITE, status WRITE, teams WRITE");
    $db->query("LOCK TABLES events WRITE, codes WRITE, quests WRITE,  teams WRITE");

    DrawTemplate(include "qu.php");
    // тут разлочим тейблу
    $db->query("UNLOCK TABLES");
    exit();
}


ob_start();

if (isset($_SESSION['msg'])) {
    echo "<h2>{$_SESSION['msg']}</h2>";
    unset($_SESSION['msg']);
} else {

    if (!isset($_GET['p'])) {
        echo <<<STR
	<div class='center'><img src='images/qrLogo.png'/></div>
<p>
<a class='button' href='?p=about'>Описание игры</a><br>
<a class='button'  href='?p=soft'>Скачать QR-распознаватель</a><br>
<a class='button'  href='?p=table'>Таблица результатов</a><br>
<a class='button'  href='reg.php'>Регистрация</a><br>
</p>

STR;
        ///<a href='?p=authors'>Создатели</a><br>

    } else {
        switch (isset($_GET['p']) ? $_GET['p'] : "") {
            case "rules":
                echo "<p>Правила тут</p>";
                break;
            case "soft":
                include "soft.php";
                break;
            case "about":
                include "about.php";
                break;
            case "reg":
                include "reg.php";
                break;
            case "table":
                include "table.php";
                break;
        }
        echo "<p><a class='button' href='?'>назад</a></p>";
    }
}
if (0) {
    $ct = time();

    echo "<p>Текущее время  по линуксу - $ct</p>";

    $hidetimevalue = 300;


    foreach ($teams as $r) {
        if ($ct - $r['hidetime'] < $hidetimevalue) // 5 минут
        {
            echo "<p>{$r['name']} &nbsp;&nbsp;&nbsp; [будут скрыты ещё " . date("i:s", $hidetimevalue - ($ct - $r['hidetime'])) . "]</p>";
        } else {
            $score = $r['score'] + $r['bonus_score'];
            echo "<p>{$r['name']} &nbsp;&nbsp;&nbsp; $score</p>";
        }
    }
}
DrawTemplate(ob_get_clean());

function DrawTemplate($body)
{
if ($body == "")
    return;
?><!DOCTYPE HTML>
<html>
<head>
    <title>QR-Battle Созвездие</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/config.js"></script>
    <script src="/js/skel.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/images/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Raleway&display=swap" rel="stylesheet">

    <noscript>
        <link rel="stylesheet" href="/css/skel-noscript.css"/>
        <link rel="stylesheet" href="/css/style.css"/>
        <link rel="stylesheet" href="/css/style-mobile.css"/>
    </noscript>
</head>
<body>
<!-- ********************************************************* -->
<div id="main">
    <div class="container">
        <div class="row main-row">
            <div class="4u">
                <section>
                    <?= $body ?>
                </section>

            </div>

        </div>

    </div>
</div>
</body>
</html><?
}
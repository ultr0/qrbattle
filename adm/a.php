<?php
include "../start.php";


if (isset($_GET['a'])) {
    if ($_GET['a'] == '321') {
        echo "ok";
        exit();
    }
    if ($_GET['a'] == '3!21') {
        echo "ok!";
        $_SESSION['admin'] = 1;
    } else
        echo crc32($_GET['a']);
}


if (isset($_GET['e']))
    unset($_SESSION);
//return;
//include "start.php";

echo '
<h1>Админка</h1>';

if (!isset($_GET['q']))
    $_GET['q'] = "main";
switch ($_GET['q']) {
    case "teams":
        include "ateams.php";
        break;
    default:
        echo '<p><a href="ateams.php">Команды</a></p>';
        echo '<p><a href="codesstat.php">Коды и статистика</a></p>';
        echo '<p><a href="create_codes.php">Создать коды</a></p>';
        echo '<p><a href="create_team_places.php">Создать места команд</a></p>';
        echo '<p><a href="spoiled.php">Использованные коды</a></p>';
        echo '<p><a href="table.php">Таблица</a></p>';
        echo '<p><a href="testsub.php">давайте испортим наши коды</a></p>';
        echo '<br>какаято ерунда<br>';
        echo '<p><a href="fillcodestable.php">обнуление и перзаполнение кодов</a></p>';
        echo '<br><br><p><a href="reset.php">Сброс всего!!!!!!!111</a></p>';
}




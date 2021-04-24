<?php
//return;
include "start.php";

if (isset($_POST['name'])) {
    $t = $_POST['name'];
    echo "$t";
    $db->query("INSERT INTO `teams` (`tid`, `name`, `score`, `hidetime`, `bonus_score`) VALUES (NULL, '$t', '', '', '')");
    header("Location: " . SITE_HTTP_ROOT . "ateams.php");
}

echo "<h2>Команды в игре<h2>";


?>
    <form method='POST' action='?a=create'>
        <p><input name='name'/><input type='submit' value='Создать команду'></p></form>
<?

echo "<form method='POST' action='?a=setbonus'>";

$res = $db->query("SELECT * FROM teams");


while ($r = $res->fetch_assoc()) {
    $score = $r['bonus_score'] + $r['bonus_score'];
    echo "<p>{$r['name']}: $score = {$r['score']} + бонус <input name='bonus[{$r['tid']}]' value='{$r['bonus_score']}'></input></p>";
}

echo "<p><input type='submit' value='Обновить бонусы'></p></form>";

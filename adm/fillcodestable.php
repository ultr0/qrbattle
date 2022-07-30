<?php

include "start.php";
$db->query("TRUNCATE TABLE events");
$db->query("TRUNCATE TABLE codes");
$db->query("TRUNCATE TABLE quests");

$qtexts = array(
    "266" => array("Какие грибы растут на пнях?","опята"),
    "267" => array("<img src='images/expedition2022/2.jpg' alt='ребус'>","дерево"),
    "268" => array("<img src='images/expedition2022/3.png' alt='ребус'>","палатка"),
    "269" => array("<img src='images/expedition2022/4.png' alt='ребус'>","костер"),
    "270" => array("<img src='images/expedition2022/5.png' alt='ребус'>","еда"),
    "271" => array("Это животное тяжелее слона? ","акула"),
    "272" => array("Сколько планет в Солнечной системе?","8"),
    "273" => array("<img src='images/expedition2022/8.png' alt='ребус'>","экспедиция"),
    "274" => array("Больше 70% земной поверхности покрыто... ","водой"),
    "275" => array("Какую рыбу называют водной свиньей? ","карп"),
    "276" => array("<img src='images/expedition2022/11.png' alt='ребус'>","бонивур"),
    "277" => array("Какой цветок до сих пор считают символом Японии и самого Солнца?","хризантема"),
    "278" => array("Произведение Чайковского в названии которого есть Лебедь?","лебединое озеро"),
    "279" => array("<img src='images/expedition2022/14.png' alt='ребус'>","вымпелы"),
    "280" => array("Какая птица самая большая в мире?","страус"),
    "281" => array("Какие грибы растут на пнях?","опята"),
    "282" => array("<img src='images/expedition2022/2.jpg' alt='ребус'>","дерево"),
    "283" => array("<img src='images/expedition2022/3.png' alt='ребус'>","палатка"),
    "284" => array("<img src='images/expedition2022/4.png' alt='ребус'>","костер"),
    "285" => array("<img src='images/expedition2022/5.png' alt='ребус'>","еда"),
    "286" => array("Это животное тяжелее слона? ","акула"),
    "287" => array("Сколько планет в Солнечной системе?","8"),
    "288" => array("<img src='images/expedition2022/8.png' alt='ребус'>","экспедиция"),
    "289" => array("Больше 70% земной поверхности покрыто... ","водой"),
    "290" => array("Какую рыбу называют водной свиньей? ","карп"),
    "291" => array("<img src='images/expedition2022/11.png' alt='ребус'>","бонивур"),
    "292" => array("Какой цветок до сих пор считают символом Японии и самого Солнца?","хризантема"),
    "293" => array("Произведение Чайковского в названии которого есть Лебедь?","лебединое озеро"),
    "294" => array("<img src='images/expedition2022/14.png' alt='ребус'>","вымпелы"),
    "295" => array("Какая птица самая большая в мире?","страус"),
    "296" => array("Какие грибы растут на пнях?","опята"),
    "297" => array("<img src='images/expedition2022/2.jpg' alt='ребус'>","дерево"),
    "298" => array("<img src='images/expedition2022/3.png' alt='ребус'>","палатка"),
    "299" => array("<img src='images/expedition2022/4.png' alt='ребус'>","костер"),
    "300" => array("<img src='images/expedition2022/5.png' alt='ребус'>","еда"),
);
$cid = 1;
$sucount = 0;

foreach ($CODES as $type => $cc) {
    for ($i = 0; $i < $cc['count']; $i++) {
        $code = substr(md5($cid . MD5SALT), 0, 16);
        $sucount += (int)$db->query("INSERT INTO codes (cid,code,type) VALUES ($cid,'$code',$type)");

        if ($type == CODE_BLUE) {
            $text = $db->real_escape_string($qtexts[$cid][0]);
            $answ = $db->real_escape_string($qtexts[$cid][1]);
            $db->query("INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')");
            //echo "INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')<br>";
        }
        $cid++;
        //break;
    }
}


echo $sucount . " things happened";
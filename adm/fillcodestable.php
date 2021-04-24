<?php

include "start.php";
$db->query("TRUNCATE TABLE events");
$db->query("TRUNCATE TABLE codes");
$db->query("TRUNCATE TABLE quests");

$qtexts=array(
"266"=>array("Как с греческого переводится слово «космос»?","Мир"),
"267"=>array("Как звали первое животное, выведенное на орбиту Земли?","Лайка"),
"268"=>array("Назовите корабль, который первым получил снимки обратной стороны Луны.","Луна-3"),
"269"=>array("Как называлась первая открытая и описанная комета?","Галлея"),
"270"=>array("Назовите фамилию имя и отчество первой в мире женщины-космонавта.","Терешкова Валентина Владимировна"),
"271"=>array("Сколько секунд будет гореть спичка на Луне? (ответ цифрой)","0"),
"272"=>array("Где сегодня на Земле день равен ночи?","На экваторе"),
"273"=>array("Когда на Луне наблюдаются «падающие» звезды?","Никогда"),
"274"=>array("Назовите фамилию имя и отчество человека, кто первым вышел в открытый космос?","Леонов Алексей Архипович"),
"275"=>array("Назовите месяц, в котором Земля ближе всего к Солнцу?","Январь"),
);

$cid=1;
$sucount=0;

foreach ($CODES as $type => $cc)
{
	for ($i=0;$i<$cc['count'];$i++)
	{
		$code = substr(md5($cid.MD5SALT),0,16);
		$sucount+=(int)$db->query("INSERT INTO codes (cid,code,type) VALUES ($cid,'$code',$type)");

		if ($type==CODE_BLUE)
		{
			$text=$db->real_escape_string($qtexts[$cid][0]);
			$answ=$db->real_escape_string($qtexts[$cid][1]);
			$db->query("INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')");
			//echo "INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')<br>";
		}
		$cid++;
		//break;
	}
}



echo $sucount. " things happened";
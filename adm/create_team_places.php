<?php

include "start.php";
if (!AdminAccess()) {
	exit;
}

require "../inc/phpqrcode/qrlib.php";


global $db;
$res=$db->query("SELECT * FROM teams");
$teams=array();
$scores=array();
while($r = $res->fetch_assoc())
{
$teams[$r['tid']]=$r;
$scores[$r['tid']]=$r['score']+$r['bonus_score'];
}

arsort($scores);

$i=1;
$tm=time(); // чтобы картинки обновлялись
foreach ($scores as $tid => $points)
{
	$text=$i." место: ".$teams[$tid]['name']." (".$points." баллов)\n";
	
	QRcode::png($text,"codes/places/$i.png", QR_ECLEVEL_L, 8, 2);
	echo $text	;
	echo "<br><img src=codes/places/$i.png?$tm><br><br>";
	
	$i++;
}


$url=SITE_HTTP_ROOT."personal.php";
QRcode::png($url, "codes/places/personal.png", QR_ECLEVEL_M, 8, 2);

echo "Ссылка на персональные результаты: <br><img src=codes/places/personal.png?$tm><br><br>";

return true;

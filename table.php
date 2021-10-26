<style>

	table,h1{margin:15px auto}
	div.center{text-align:center}
	div.center *{display:inline-block}
	h2 {margin:5px}
	
	tr td{padding:5px!important;}
	
	tr:nth-child(even) {
		background-color: #fcefa1 ;
	}
	
	tr:nth-child(odd) {
		background-color: #A6C8FF;
	}
	p{margin:5px auto;vertical-align:middle;text-align:center}
</style>
<?php


$ct=time();
$teams=array();

/** @global $db */
$res=$db->query("SELECT * FROM teams WHERE hidetime=0 ORDER BY score DESC,name ");
while($r = $res->fetch_assoc())
{
	$teams[$r['tid']]=$r;
}
$res=$db->query("SELECT * FROM teams WHERE hidetime!=0 ORDER BY name");
while($r = $res->fetch_assoc())
{
	$teams[$r['tid']]=$r;
}

//	echo "<p>Текущее время  по линуксу - $ct</p>";
echo "<div class='center'><img src='images/qrLogo.png'/> <h2>Таблица результатов</h2><img src='/images/qrcode.png' width='120px' /></div>";
$hidetimevalue=3600;
echo "<table>";
$i=1;
foreach ($teams as $r)
{
	echo "<tr>";
	//		if ($ct-$r['hidetime']<$hidetimevalue) // 5 минут
	if ($r['hidetime']) // 5 минут
	{
		echo "<td>&nbsp; </td><td>{$r['name']} </td><td> баллы скрыты</td>";
		//		echo "<li>{$r['name']} &nbsp;&nbsp;&nbsp; [будут скрыты ещё ".date("i:s",$hidetimevalue-($ct-$r['hidetime']))."]</li>";
	}
	else
	{
		$score=	$r['score'] + $r['bonus_score'];
		echo "<td>$i. </td><td>{$r['name']} </td><td> $score</td>";
	}
	echo "</tr>";
	$i++;
}
echo "</table>";
?>

<script>
window.setTimeout("location.reload()",5000);
</script>

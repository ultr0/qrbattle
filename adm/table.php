<?php
include "start.php";
if (!AdminAccess())
	exit();
?>
<!DOCTYPE HTML>
<!--suppress CssOverwrittenProperties -->
	<html>
<head>
	<title>QR-Battle Созвездие</title>
	<script src="../js/jquery.min.js"></script>

	<style>
	*{font-family:sans-serif}
	td {padding:0 10px}
	table,h1{margin:0 auto}
	body{width:100%;height:100%;margin:10px;padding:0;}
	html{width:100%;height:100%;}
	div.center{text-align:center}
	div.center *{display:inline-block}
	h2 {margin:5px}

	tr:nth-child(even) {
		background-color: #fcefa1 ;
	}

	tr:nth-child(odd) {
		background-color: #A6C8FF;
	}
	p{margin:5px 0 0;vertical-align:middle}
		</style>
<script>
	var im=null;
	var interv=null;
	function upd()
	{
		if (im!=null)
			window.location=im;

	}
</script>
</head>
<body onload='setInterval("upd()",1000);' style='margin:0'>


<?
if (!isset($_GET['p']))
{
?>
<button onclick='openwin()'>Открыть окно</button> <br>

	<button onclick='setwin("?p=table")'>Таблица</button> <input type='checkbox' id='cbself'>В это окно<br>

	
	<a target=_blank href=create_team_places.php>сгенерировать места команд</a>
<br>

	<button onclick='setwin("?p=place&n=5")'>5 место</button>
	<button onclick='setwin("?p=place&n=4")'>4 место</button>
	<button onclick='setwin("?p=place&n=3")'>3 место</button>
	<button onclick='setwin("?p=place&n=2")'>2 место</button>
	<button onclick='setwin("?p=place&n=1")'>1 место</button>
	<button onclick='setwin("?p=place&n=personal")'>персональный счёт</button>
	<br>
<script>
	var w=null;
	function openwin()
	{
		w=window.open("table.php","blablas");
	}

	function setwin(str)
	{
		if ($("#cbself").is(':checked'))
		{
			window.location=str;
			return;
		}
		w.im=str;
	//	location("?"+str);
	//	w.f1();
	}
	

</script>
<?
}
else
	switch($_GET['p'])
	{
		case "place":
			
			$n=$_GET['n'];
			$tm=time();
			$label=is_numeric($n)?$n."<br>место":"Персональный<br>счёт";
			
?>
	<table style='width:100%;height:100%;display:none' id='outerdiv'>
		<tr style='background:transparent'>
		<td style='width:20%;text-align:center;vertical-align:middle'>
		<span style='display:inline-block;margin-top:-1.2em;margin-left:1em; font-size: 1em;   font-size: 3vw;'><b><?=$label?></b></span>
		</td>
		<td style='width:79%;text-align:center;vertical-align:middle'>
			<img src='codes/places/<?=$n.".png?".$tm;?>' style='height:95%'/>
		</td>
		</tr>
	</table>

			<script>
				$(function(){
					$("#outerdiv").delay(1000).fadeIn(1000);
					$(document).keypress(function(e)
					{
						switch (e.which)
						{
							case 49: window.location="?p=place&n=1";break;
							case 50: window.location="?p=place&n=2";break;
							case 51: window.location="?p=place&n=3";break;
							case 52: window.location="?p=place&n=4";break;
							case 53: window.location="?p=place&n=5";break;
							default:
					//			alert(e.which);
					//			break;
								return;
						}
						e.preventDefault();
					});
				});


			</script>

	
	<?php
			break;
		case "table":
			?>
	<script>
		window.setTimeout("location.reload()",5000);
	</script>
<?


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
			echo "<div class='center'><h2>Таблица результатов</h2></div>";
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
			break;
	}
	echo "</body>
</html>";
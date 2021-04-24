<?php
// start -> index.php


global $CODES,$teams;
//if (!AdminAccess())
//{
//	return "<p>Вы нашли код для игры QR-Battle. Игра ещё началась!</p>";
//}

$file='counter.txt';
$cnt=file_get_contents($file);
$cnt++;
file_put_contents($file,$cnt);

$ips=file_get_contents('ips.txt');
file_put_contents('ips.txt',$ips.$_SERVER['REMOTE_ADDR']."\n");

global $db;
$code=$db->real_escape_string($_GET['q']);
// есть ли такой код?
$res=$db->query("SELECT * FROM codes WHERE code='$code'");

$html=<<<STR
<html>
<head>
	<style>body {font-size: 100%;}</style>
</head>
<body>
STR;

if (!$res->num_rows) // не найден код
{
	$ips=file_get_contents('wrongcounter.txt');
	file_put_contents('wrongcounter.txt',$ips.$_SERVER['REMOTE_ADDR']."\n");

	header("Location: ".SITE_HTTP_ROOT);


	return "";
}

if ($r=$res->fetch_assoc())
{
	$cid=$r['cid'];
	$type=$r['type'];
	$name=$CODES[$r['type']]['name'];
//	$html.="<p>Код найден! Номер кода: {$r['cid']}, тип: $name</p>";
	if (AdminAccess())
	{
		$db->query("UPDATE codes SET spoiled=1 WHERE cid='$cid'");
		return $html."Code $cid/$type has been spoiled!";
	}
}
else
	return $html."Данный код не активен. Возможно он со старой игры"; // просто потому что 23 строка раньше была

$res=$db->query("SELECT * FROM events WHERE cid='$cid'");

$events=array();

while ($r=$res->fetch_assoc())
{
	$events[]=$r;
}
$html.="<p>".$CODES[$type]['desc']."</p>";
$action="";
switch($type)
{
	case CODE_WHITE:
		if (count($events)==count($teams)) // список команд обычно статичен
		{
			$action="used";
			break;
		}
		if(isset($_COOKIE["TeamQR"]))
		{
			$_POST['team']=$_COOKIE["TeamQR"];
		}
		else
		{
			header('Location: http://qrbattle.ru/?p=reg');
			exit;
		}
		// надо спросить какая команда подписывает
		$action ="askteam";
		// если команда уже подписывала, то ещё раз не может подписывать
		break;
	case CODE_GREEN:
		if (count($events)==3) // 3-х разовый код
		{
			$action="used";
			break;
		}
		if(isset($_COOKIE["TeamQR"]))
		{
			$_POST['team']=$_COOKIE["TeamQR"];
		}
		else
		{
			header('Location: http://qrbattle.ru/?p=reg');
			exit;
		}
		// надо спросить какая команда подписывает
		$action ="askteam";
		// если команда уже подписывала, то ещё раз не может подписывать
		break;
	case CODE_BLUE:
	case CODE_RED:
	case CODE_HIDESCORE:
		if (count($events)==1) // одно-разовый код
		{
			$action="used";
			break;
		}
		// надо спросить какая команда подписывает
		$action ="askteam";
		// если команда уже подписывала, то ещё раз не может подписывать
		// но не для хайдскор
		break;
}

switch($action)
{
	case "used":
		return $html."<h2>Этот код уже использован</h2>";
	case "askteam":
		$action="";
		if (isset($_POST['team']))
		{
			$tid=$_POST['team'];
			if (!isset($teams[$tid])) // team doesnt exist
			{
				$action="badteam";
			}
			else
			{
				foreach ($events as $ev)
				{
					if ($ev['tid']==$tid && $type!=CODE_HIDESCORE) // нельзя несколько раз одной команде фигачить один и тот же код
					// кроме хайдскор
					{
						$teamname=$teams[$tid]['name'];
						$action="teamused";
						break;
					}
				}
				if ($action=="") // ещё не определились
				{	// можно добавить код и команда подходит

					if ($type==CODE_BLUE) // у красного кода надо ответ проверить
					{
						$answ=$db->real_escape_string($_POST['answ']);
						$res=$db->query("SELECT cid FROM quests WHERE cid='$cid' AND answ='$answ'");
						if ($res->num_rows==0)
						{
							$action="wronganswer";
						}
						else
							$action="";
					}
					if ($action=="") // вот так
					{
						$action="process";
						break;
					}
				}
			}
			
			if ($action=="teamused") {
				// по тупому сделано, но $teamname в этом месте инициализировано
				/** @noinspection PhpUndefinedVariableInspection */
				// todo переделать на Ваша команда уже использовала этот
				$html.="<p style='color:red'>Данный код использован для команды  '$teamname'</p>";
				break;
			}
			if ($action=="badteam") {
				$html.="<p>Попробуйте ещё раз...</p>"; // team doesnt exist
				break;
			}
			
		}
		// иначе команды ещё нет, надо вывести список команд

		$html.="<form id='teamsform' method='post' action='?q={$_GET['q']}'>";

		if ($type==CODE_BLUE)
		{
			$html.="<h2>Задача!</h2>";
			if ($action=="wronganswer")
				$html.="<p style='color:red'>Неверный ответ!</p>";
			$res=$db->query("SELECT txt FROM quests WHERE cid='$cid'");
			$qrow=$res->fetch_assoc();
			$html.="<p>{$qrow['txt']}</p>";
			//$html.="<p style='color:blue'>Введите ответ и выберите команду для зачисления очков:</p>";
			$html.="<p><input type='text' name='answ'/></p>";
		}
		else
			$html.="<h2>Выберите команду</h2><p>";

		$html.="<p style='color:green' id='postmsg'></p>";
		// indian style... -_-
		$randarray=array();
		foreach ($teams as $tid => $r)
		{
			if($tid!=$_COOKIE["TeamQR"])
				$randarray[]=$tid;
		}
		shuffle($randarray);

	//	foreach ($teams as $tid => $r)
		foreach ($randarray as $tid)
		{
			$r=$teams[$tid];
			$html.="<button type='submit' class='button' name='team' value='$tid'>{$r['name']}</button>";
		}

		$html.=<<<STR
</p></form>
<script>

$("#teamsform").submit(function()
{
//	$("#teamsform .button").hide("disabled","disabled");
	$("#teamsform .button").hide();
	$("#postmsg").html("Идёт отправка...");
	return true;
});

</script>
STR;
		break;

}

// тоже по тупому сделано, но $tid вероятно тоже есть
/** @noinspection PhpUndefinedVariableInspection */
$teamname=$teams[$tid]['name'];

if ($action=="process")
{

	$msg="";
	$cid=$db->real_escape_string($cid);
	$tid= $db->real_escape_string($tid);
	$ts=$db->real_escape_string(time());

	$sid=session_id();
	$db->query("INSERT INTO events(cid,tid,ts,sid) VALUES ('$cid','$tid','$ts','$sid')");
	// делаем перерасчёт очков команд
	switch($type)
	{
		case CODE_WHITE:
			foreach($events as $ev) // +1 получают уже подписанные в этом коде команды
			{
				$db->query("UPDATE teams SET score=score+1 WHERE tid={$ev['tid']}");
			}
			$db->query("UPDATE teams SET score=score+1 WHERE tid=$tid"); // и целевая команда
			$msg="Команде '$teamname' начислен 1 балл";
			break;
		case CODE_GREEN:
			$greenpoints=array(20,15,10);
	//		dbg($events);
			$pts=$greenpoints[count($events)];
	//		dbg($pts);
			$db->query("UPDATE teams SET score=score+$pts WHERE tid=$tid"); // и целевая команда
			$msg="Команде '$teamname' начислено $pts баллов";
			break;
		case CODE_BLUE:
			$db->query("UPDATE teams SET score=score+20 WHERE tid=$tid"); // тут ответ
			$msg="Команде '$teamname' начислено 20 баллов";
			break;
		case CODE_RED:
			$db->query("UPDATE teams SET score=score-20 WHERE tid=$tid"); // и целевая команда
			$msg="У команды '$teamname' сняты 20 баллов";
			break;
		case CODE_HIDESCORE:
			$res=$db->query("SELECT hidetime FROM teams WHERE tid='$tid'"); // внутри лок тейбл получаем эту инфу

			if(!($r=$res->fetch_assoc()))
				return $html."Случилась пичалька... попробуйте ещё раз";
			dbg($r);

			$hidetime=$r['hidetime'];
			dbg($hidetime);
			if ($hidetime==0)
			{
				$db->query("UPDATE teams SET hidetime='".time()."' WHERE tid=$tid");
				$msg="$hidetime У команды '$teamname' баллы <strong>скрыты</strong>";
			}
			else
			{
				$db->query("UPDATE teams SET hidetime='0' WHERE tid=$tid");
				$msg="У команды '$teamname' баллы <strong>отображаются</strong>";
			}
			break;
	}
	$_SESSION['msg']=$msg;
	// ок, код отправлен, очки начислены, конфетки съедены
//	dbg($msg);
//	return "";
	header("Location: ".SITE_HTTP_ROOT."?ok");
	return "";
}

return $html;







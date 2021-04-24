<?php
include "../start.php";


if (isset($_GET['a']))
{
	if ($_GET['a']=='321')
	{
		echo "ok";
		exit();
	}
	if ($_GET['a']=='3!21')
	{
		echo "ok!";
		$_SESSION['admin']=1;
	}
	else
		echo crc32($_GET['a']);
}




if (isset($_GET['e']))
	unset($_SESSION);
//return;
//include "start.php";

echo '
<h1>Админка</h1>';

if (!isset($_GET['q']))
	$_GET['q']="main";
switch($_GET['q'])
{
	case "teams":
		include "ateams.php";
		break;
	default:
		echo '<p><a href="ateams.php">Команды</a></p>';
}




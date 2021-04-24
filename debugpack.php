<?php
/* DebugPack 
	1.6 (15.06.2012) by Nazar@OKT
	1.5 (10.01.2012) by Nazar@OKT
prev
	1.4.1 (26.07.2011) by Nazar@OKT
*/
//include_once $_SERVER['DOCUMENT_ROOT']."/../debugpack.php";
if (isset($DEBUG_ALLOWED_IP)) // типа метки что файл уже подключен
	exit;

class mysqli_dbg extends mysqli
{
	function mysqli_dbg($a, $b, $c,$d)
	{
		//$this->__construct($a,$b,$c,$d);
		$this->mysqli($a,$b,$c,$d);
	}

	function query($qu,$resultmode=0)
	{
//		dbg($qu);
		$ret=parent::query($qu,$resultmode);
		if (!$ret)
		{
			dbg(debug_backtrace()[0]['file']);
			dbg($qu);
			dbg($this->error);
			die(1);
		}
		return $ret;
	}
}

function GetRealIP()
{
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	return $_SERVER["REMOTE_ADDR"];
}

if (!isset($DEBUG_RESTRICT_IP))
	$DEBUG_RESTRICT_IP = true;
$DEBUG_ALLOWED_IP = array
	(
	"10.10.10.227",
//	"10.10.10.226",
//	"10.10.10.228"
	);
function TestRestriction()
{
	if ($GLOBALS["DEBUG_RESTRICT_IP"])
	{
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$proxy=in_array($_SERVER["HTTP_X_FORWARDED_FOR"],$GLOBALS["DEBUG_ALLOWED_IP"]);
		else
			$proxy=false;
		
		if (!(in_array($_SERVER["REMOTE_ADDR"],$GLOBALS["DEBUG_ALLOWED_IP"])||$proxy))
			return true;
	}
	return false;
}

function DbgNone()
{
	$GLOBALS["DEBUG_RESTRICT_IP"]=true;
	ErrorsON();
	error_reporting(0);
	ini_set('display_errors', 0);
}

function DbgAll($sql=false,$noticeoff=false)
{
	$GLOBALS["DEBUG_RESTRICT_IP"]=false;
	ErrorsON();
	if ($sql)
		ErrorsMySQL();

	if ($noticeoff)
		error_reporting(E_ALL&~E_NOTICE);
}

function DbgAllow($ip)
{
	$GLOBALS["DEBUG_ALLOWED_IP"][]=$ip;
}
function ErrorsON($ip="")
{
//	echo $ip;
	$GLOBALS["DEBUG_ALLOWED_IP"][]=$ip;
	if (TestRestriction())
		return;
	error_reporting(E_ALL);
	if (!ini_get('display_errors'))
		ini_set('display_errors', 1);
	//$TRACE_DEBUGS=1;
}
function ErrorsMySQL()
{
	// на мускули хз как
}

function test()
{
	echo time()%1000;
}

function WLE()
{
	if (TestRestriction())
		return;

	if (($errinfo = error_get_last())==NULL

		||

		strstr($errinfo["message"],"Undefined offset"))
	{
		?><span style='color:blue'> (ошибок нет)</span> <?
	}
	else
		echo "<p><b><font color=red>ERROR ".$errinfo["type"].
			"</font> on line <font color=blue><b> ".$errinfo["line"]."</b></font>".
			"</b> - ".$errinfo["message"]." in <b>".$errinfo["file"].
			"</b> </p>";

//	if (mysql_error()!="")
//	{
//		echo mysql_error();
//	}

}

function dbf($some)
{
	if (TestRestriction())
		return;

	?> <div style="position:fixed;z-index:99;top:0;left:0;padding:5px;border:1px blue solid;background-color:white;" id="DEBUG_FIXED_WINDOW">
	<style type="text/css">#DEBUG_FIXED_WINDOW:hover{opacity:0.0;}</style>
	<?
	dbg($some);
	?> </div><?
}


function dbg($some,$white=false)
{
	if (TestRestriction())
		return;
	// echo "<pre>";
	// var_export(debug_backtrace());
	// echo "</pre>";
	
	$red=$white?"white":"red";
	$black=$white?"white":"black";
	echo "<div style='font-size:14px;white-space:pre-wrap;font-family:\"courier new\"'><font color=$red>#</font>";
	echo "<font color=$black>";
	echo htmlspecialchars(print_r($some,true),ENT_QUOTES,'cp1251');
	echo "</font>";
	echo "<font color=$red>#</font></div>";
}


function Debun($some,$name)
{
	echo "<font color=red>#</font>$name<font color=red>#</font><pre>";
	print_r($some);
	echo "</pre><font color=red>#</font>";
}

function DbgTimeStart()
{
	$GLOBALS['dbg_time_start'] = microtime(true);
}

function DbgTimeEnd($do_echo=false)
{
	$script_time_end = microtime(true);
	$dtt = round($script_time_end - $GLOBALS['dbg_time_start'],3);
	if ($do_echo)
		echo "<div>Time: $dtt</div>";
	return $dtt;
}
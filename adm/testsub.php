<?php

include "start.php";

if (!AdminAccess())
	exit();
if (!isset($_GET['t']))
{
	for ($i=1;$i<=300;$i++)
	{
		echo "<a href='testsub.php?t=$i'>$i</a> ";
	}
	return;
}
$res=$db->query("SELECT code FROM codes WHERE cid=".$_GET['t']);

$r=$res->fetch_assoc();

if (!$r['code'])
{


//	dbg("wat");
	die("wat");

}



header("Location: ".SITE_HTTP_ROOT."?q=".$r['code']);
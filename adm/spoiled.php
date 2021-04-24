<?php

include "start.php";


if (!AdminAccess())
	exit();
$res=$db->query("SELECT * FROM events e,codes c WHERE e.cid=c.cid ORDER BY e.ts");

$n=0;
$last=0;
while($r=$res->fetch_assoc())
{
//	if ($last==$r['cid'])
//		continue;
	$last=$r['cid'];
	if ($r['spoiled'])
	{
		$n++;
		$style=" style='background:#ff99ff'";
	}
else
	$style="";
	echo "<span $style>".$r['cid']."</span> ";

}

echo "<br>$n";
return;

while($r=$res->fetch_assoc())
{

	$found[]=$r['cid'];
}
?>
<style>
	span {margin:0 3px;}
</style>
<?
$n=0;
	for ($i=1;$i<=300;$i++)
	{

		if (in_array($i,$found))
		{
			$n++;
			$style=" style='background:#ff99ff'";
		}
		else
			$style="";
		echo "<span $style>$i</span> ";
	}

echo "<br>Total Found: $n";


<?php
include "start.php";

$vips=array(
99 =>	"n9kcdpolkvnrgbe59v2bri39e1",
43 =>	"kk8d9qfl8fvr11s52bp89pf140",
38=>	"r97b9tppqnlo0p48lbq0evi477",
37=>	"3i9m18v3s2dsnkshddju57fc51",
35=>	"5mrathre61lvtks8vuvrr00l14",
34=>	"00h2emq9e9r86diut96l28l0g7"
);


$sid=session_id();

foreach ($vips as $key => $v)
{
	if ($sid==$v)
	{
		DrawTemplate("Ваш персональный счёт: ".$key);
		return;
	}
}
DrawTemplate("Вы не попали в список призёров");


function DrawTemplate($body)
{
	if ($body=="")
		return;
?><!DOCTYPE HTML>
<html>
<head>
	<title>QR-Battle Созвездие</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<script src="js/jquery.min.js"></script>
	<script src="js/config.js"></script>
	<script src="js/skel.min.js"></script>
	<noscript>
		<link rel="stylesheet" href="css/skel-noscript.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/style-mobile.css" />
	</noscript>
</head>
<body>
<!-- ********************************************************* -->
<div id="main">
	<div class="container">
		<div class="row main-row">
			<div class="4u">
				<section>
					<?=$body?>
				</section>

			</div>

		</div>

	</div>
</div>
</body>
</html><?}
return;

$res=$db->query("SELECT * FROM events e,codes c WHERE e.cid=c.cid ORDER BY eid");

$codes = array();
$personal = array();
while($r=$res->fetch_assoc())
{
	switch($r['type'])
	{
		case CODE_WHITE:
			$score=2;
			break;
		case CODE_GREEN:
			$score=1;
			break;
		case CODE_BLUE:
			$score=20;
			break;
		case CODE_RED:
		case CODE_HIDESCORE:
			break;
	}
}
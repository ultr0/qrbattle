<?php

include "start.php";


if (!AdminAccess())
    exit();
$res = $db->query("SELECT DISTINCT c.cid,spoiled FROM events e,codes c WHERE e.cid=c.cid");
$all = $db->query("SELECT cid,code,type FROM codes");
$allCodes = array();
$i = 0;
while ($a = $all->fetch_assoc()) {
    $allCodes[$i++] = $a;
}
$found = array();
while ($r = $res->fetch_assoc()) {
    $found[] = $r['cid'];
}
?>
    <style>
        span {
            margin: 0 3px;
        }
    </style>
<?
$n = 0;
for ($i = 1; $i <= count($allCodes); $i++) {

    if (in_array($i, $found)) {
        $n++;
        $style = " style='background:#ff99ff'";
    } else
        $style = "";
    echo "<span $style>$i</span> ";
}

echo "<br>Total Found: $n";
<?php
return;
include "start.php";

$db->query("UPDATE `teams` SET `score`=0,`bonus_score`=0,`hidetime`=0");

$db->query("TRUNCATE TABLE events");

echo "ok";
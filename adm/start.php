<?php
include "../start.php";

if (!AdminAccess()) {
	header("Location: /");
	exit();
}

DbgAll(1);
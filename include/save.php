<?php

//p_log("de.txt", "h");

if (!isset($_POST["id"]) ||
	!isset($_POST["date"]) ||
	!isset($_POST["rounds"]) ||
	!isset($_POST["reps"]) ||
	!isset($_POST["descr"]))
	return false;

$workout	= intval($_POST["id"]);
$date		= strip_tags(substr($_POST["date"], 0, 10));
$rounds		= intval($_POST["rounds"]);
$reps		= floatval(str_replace(",", ".", $_POST["reps"]));
$descr		= strip_tags(substr($_POST["descr"], 0, 255));

$d->query(
	"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
	[ $date, $workout, $rounds, $reps, $descr, date("Y-m-d H:i:s") ]
);

if ($d->result)
	echo "OK";
else
	echo "NOK";

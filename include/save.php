<?php

if (isset($_POST["cell_id"])) {
	list($tmp, $date, $workout) = explode("_", clean_input($_POST["cell_id"]));
	$value = clean_input($_POST["value"]);
	//$type = $

	if (!$workout)
		return false;

	$d->query("delete from workout where date = ? && workout_id = ?", [ $date, $workout ]);

	$d->query(
		"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
		[ $date, $workout, 0, 0, $value, date("Y-m-d H:i:s") ]
	);

	if ($d->result) {
		if (!$value)
			echo "-";
		else
			echo $value;
	}
	else
		echo "NOK";
}
elseif (isset($_POST["id"])) {
	$workout	= intval($_POST["id"]);
	$date		= clean_input(substr($_POST["date"], 0, 10));
	$rounds		= intval($_POST["rounds"]);
	$reps		= floatval(str_replace(",", ".", $_POST["reps"]));
	$descr		= clean_input(substr($_POST["descr"], 0, 255));

	$d->query(
		"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
		[ $date, $workout, $rounds, $reps, $descr, date("Y-m-d H:i:s") ]
	);

	if ($d->result)
		echo "OK";
	else
		echo "NOK";
}
else {
	echo "NOK";
}

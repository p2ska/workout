<?php

if (isset($_POST["cell_id"])) {
	$rounds = $reps = 0;
	$descr = "";

	list($tmp, $date, $workout) = explode("_", clean_input($_POST["cell_id"]));
	$value = clean_input($_POST["value"]);

	if (!$workout)
		return false;

	$d->query("select type from workouts where id = ?", [ $workout ]);

	$r = $d->get_obj();

	switch ($r->type) {
		case "rounds_reps":
			if (substr_count($value, "x")) {
				list($rounds, $reps) = explode("x", $value);

				$rounds = intval($rounds);
				$reps = intval($reps);
			}
			else {
				$rounds = 3;
				$reps = intval($value);
				$value = $rounds. "x". $reps;
			}

			break;

		case "value":
			$reps = floatval(str_replace(",", ".", $value));

			break;

		case "textarea":
			$descr = trim($value);

			break;
	}

	$d->query("delete from workout where date = ? && workout_id = ?", [ $date, $workout ]);

	if ($rounds == 0 && $reps == 0 && (!$descr || $descr == "-")) {
		echo "-";

		return false;
	}

	$d->query(
		"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
		[ $date, $workout, $rounds, $reps, $descr, date("Y-m-d H:i:s") ]
	);

	if ($d->result)
		echo $value;
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

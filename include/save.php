<?php

if (isset($_POST["workout"])) {
	$rounds = $reps = 0;
	$descr = "";

	$date	= clean_input($_POST["date"]);
	$workout= clean_input($_POST["workout"]);
	$value	= clean_input($_POST["value"]);

	$r = $d->query("select type from workouts where id = ?", [ $workout ], true);

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

	if ($reps == 0 && (!$descr || $descr == "-")) {
		echo "-";

		return false;
	}

	$d->query(
		"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
		[ $date, $workout, $rounds, $reps, $descr, date("Y-m-d H:i:s") ]
	);

	if ($d->result)
		echo $value;
	else {
        p_log("db_failure.txt", $d);

		echo "NOK";
    }
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
	else {
        p_log("db_failure.txt", $d);

		echo "NOK";
    }
}
else {
    p_log("gn_failure.txt", $_POST);

	echo "NOK";
}

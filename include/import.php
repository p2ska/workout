<?php

// import

/*
$d->query("select * from workouts");

foreach ($d->get_all() as $o)
	$workouts[$o->sort] = $o->id;

$d->query("truncate workout");

$fp = fopen("import.csv", "r");

while ($line = fgets($fp, 1024)) {
	if ($line) {
		$ex = explode(";", $line);

		list($dd, $m) = explode(".", $ex[0]);
		if ($m == "Aug") $m = "08";
		if ($m == "Sep") $m = "09";
		if ($m == "Oct") $m = "10";

		$date = "2016-". $m. "-". $dd;

		for ($a = 1; $a <= 18; $a++) {
			if (isset($ex[$a]) && $ex[$a]) {
				if (!trim($ex[$a]))
					continue;

				$rounds = $reps = $descr = "";

				if ($a >= 10) {
					$ex1 = explode("!", $ex[$a]);
					$rounds = 3;
					$reps = $ex1[0];
				}
				else {
					$rounds = 1;
					$reps = str_replace(",", ".", $ex[$a]);

					if ($a == 6 || $a == 8) {
						$reps = 1;
						$descr = mysql_real_escape_string($ex[$a]);
					}
				}

				$d->query(
					"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
					[ $date, $workouts[$a], $rounds, $reps, $descr, date("Y-m-d H:i:s") ]
				);
			}
		}
	}
}

die();

*/

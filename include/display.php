<?php

$today = time();

for ($day = 0; $day < 30; $day++) {
	$d->query("select id from workout where date = ? limit 1", [ date("Y-m-d", $today - $day * 86400) ]);

	if (!$d->rows) {
		$d->query(
			"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
			[ date("Y-m-d"), 13, 0, 0, "", date("Y-m-d H:i:s") ]
		);
	}
	else {
		break;
	}
}

$d->query("select * from workouts order by sort");

foreach ($d->get_all() as $o) {
	$workouts_id[$o->id] = $workouts[$o->sort] = $o;
}

if (isset($p->args[0]) && $p->args[0] == "element") {
	$id = intval($p->args[1]);
	$date = $p->args[2];

	$d->query("select * from workout where workout_id = ? && date = ? order by id desc limit 1", [ $id, $date ]);
}
else {
	$d->query("select * from workout order by date desc, id");
}

foreach ($d->get_all() as $o) {
	$workout = $workouts[$o->workout_id]; // siin oleks vaja hoopis sort järgi

	$results[$o->date][$workout->sort] = [
		"name"		=> $workout->name,
		"type"		=> $workout->type, // ?!?!
		"date"		=> $o->date,
		"rounds"	=> $o->rounds,
		"reps"		=> $o->reps,
		"descr"		=> $o->descr
	];
}

if (isset($p->args[0]) && $p->args[0] == "element") {
	$result = array_shift($results);
	echo results($p, $workouts_id[$id], $result);
}
elseif (isset($p->args[0]) && $p->args[0] == "header") {
	echo "<div class='descr date'>Kuupäev</div>";

    if (!isset($workouts))
        return false;

    foreach ($workouts as $w)
		echo "<div id='w_". $w->id. "' class='descr ". $w->name. "' data-workout='". $w->id. "'>". $w->title. "</div>";

	echo "<br/>";
}
else {
	if (!isset($results))
		return false;

	$date_width = "";

	if (isset($p->args[1])) {
		$column_widths = explode("-", $p->args[1]);
		$date_width = " style='width: ". array_shift($column_widths). "px'";
	}

	$row = 0;
	$last_month = false;

	foreach ($results as $date => $result) {
		$today = "";
		$row++;
		$p->date = $date;

		list($yy, $mm, $dd) = explode("-", $date);
		$f_date = intval($dd). ". ". $months[intval($mm) - 1];
		$wd = date("w", mktime(0, 0, 0, $mm, $dd, $yy));

		if ($row > 0 && $row < 3)
			$today = " today";

		if ($last_month && $last_month != $mm)
			$today .= " new_month";

		if ($wd == 0 || $wd == 5)
			echo "<div class='weekend results". $today. "'>";
		else
			echo "<div class='results". $today. "'>";

		echo "<div class='date'". $date_width. ">". $f_date. "</div>";

		$count = 0;
		$width = false;

		foreach ($workouts as $w) {
			if (isset($column_widths))
				$width = $column_widths[$count++];

			echo results($p, $workouts_id[$w->id], $result, $width);
		}

		echo "</div>";

		$last_month = $mm;
	}
}

function results($p, $workout, $value, $width = false) {
	$val = "-";
	$bg = "";

	if (isset($value[$workout->id])) {
		switch ($workout->type) {
			case "rounds_reps":	$val = $value[$workout->id]["rounds"]. "x". $value[$workout->id]["reps"]; break;
			case "value":		$val = $value[$workout->id]["reps"]; break;
			case "textarea":	$val = $value[$workout->id]["descr"]; break;
			case "plank":		$val = $value[$workout->id]["reps"]; break;
		}
	}

	if (!$width) {
		if (!$val)
			$val = "-";

		return $val;
	}

	if ((is_string($val) && ($val == "" || $val == "-")) || (!is_string($val) && $val == 0)) {
		$val = "-";
		$bg = " none";
	}

	$result = "<div id='f_". $p->date. "_". $workout->id. "' class='value ". $workout->name. $bg. "'";
	$result.= ($width ? " style='width: ". $width. "px'" : ""). ">". $val. "</div>";

	return $result;
}

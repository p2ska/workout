<?php

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

if ($d->rows == 0) {
	echo "-";

	return false;
}

foreach ($d->get_all() as $o) {
	$workout = $workouts[$o->workout_id];

	$results[$o->date][$workout->sort] = [
		"name"		=> $workout->name,
		"type"		=> $workout->type,
		"date"		=> $o->date,
		"rounds"	=> $o->rounds,
		"reps"		=> $o->reps,
		"descr"		=> $o->descr
	];
}

if (isset($p->args[0]) && $p->args[0] == "element") {
	$result = array_shift($results);

	echo results($d, $p, $workouts_id[$id], $result);
}
elseif (isset($p->args[0]) && $p->args[0] == "header") {
	echo "<div class='descr date'>Kuupäev</div>";

	if (!isset($workouts))
		return false;

	foreach ($workouts as $w)
		if (!$w->hide)
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
		$cd = $today = "";
		$row++;
		$p->date = $date;

		list($yy, $mm, $dd) = explode("-", $date);

		$wd = date("w", mktime(0, 0, 0, $mm, $dd, $yy));
		$cd = $weekdays[$wd]. ", ";

		if ($wd == 0 || $wd == 6)
			$cd = "<font class='we'>". $cd. "</font>";

		$f_date = $cd. intval($dd). ". ". $months[intval($mm) - 1];

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
			if ($w->hide)
				continue;

			if (isset($column_widths))
				$width = $column_widths[$count++];

			echo results($d, $p, $workouts_id[$w->id], $result, $width);
		}

		echo "</div>";

		$last_month = $mm;
	}
}

function results($d, $p, $workout, $value, $width = false) {
	$val = "-";
	$bg = "";

	$today_ts = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

	if (isset($value[$workout->id])) {
		switch ($workout->type) {
			case "rounds_reps":	$val = $value[$workout->id]["rounds"]. "x". $value[$workout->id]["reps"]; break;
			case "value":		$val = $value[$workout->id]["reps"]; break;
			case "textarea":	$val = $value[$workout->id]["descr"]; break;
		}
	}

	if (!$width) {
		if (!$val)
			$val = "-";

		return $val;
	}

	if ((is_string($val) && $val == "") || (is_numeric($val) && $val == 0))
		$val = "-";

	if ($val != "-" && ($workout->id == 13 || $workout->id == 14))
		$val = sprintf("%.1f", $val);

	if ($val == "-")
		$bg = " none";

	if ($val == "-" && $p->date == date("Y-m-d") && $workout->suggestions) {
		$d->query("select date, rounds, reps from workout where workout_id = ? order by added desc limit 5", [ $workout->id ]);

		$suggestion = false;

		if ($d->rows) {
			$suggestions = explode("-", $workout->suggestions);

			$latest = $d->get_all();

			$last = $latest[0];
			$last_ts = strtotime($last->date);

			$days_ago = ($today_ts - $last_ts) / 86400;

			if ($days_ago >= $suggestions[2])
				$suggestion = "strong";
			elseif ($days_ago >= $suggestions[1])
				$suggestion = "normal";
			elseif ($days_ago >= $suggestions[0])
				$suggestion = "mild";

			/*
			foreach ($latest as $l) {
				if ($workout->type == "rounds_reps") {
					$next_rounds = $l->rounds;
					$next_reps = $l->reps + 1;
				}
				else {
					$next_reps = $l->reps + 1;
				}
			}
			*/

			if ($suggestion) {
				$next_rounds = $next_reps = false;

				if ($workout->type == "rounds_reps") {
					$next_rounds = $last->rounds;

					if (in_array($workout->id, [ 2, 6, 8 ]))
						$next_reps = $last->reps;
					else
						$next_reps = $last->reps + 1;
				}
				else {
					if (in_array($workout->id, [ 10, 15, 16, 17 ]))
						$next_reps = $last->reps;
					else
						$next_reps = $last->reps + 1;
				}

				if ($next_rounds)
					$next = $next_rounds. "x". $next_reps;
				else
					$next = $next_reps;

				$val = $next;
			}
		}
	}
	else {
		$suggestion = false;
	}

	$result = "<div id='f_". $p->date. "_". $workout->id. "' ";
	$result.= "class='value ". ($suggestion ? " suggestion_".$suggestion. " " : ""). $workout->name. $bg. "'";
	$result.= ($width ? " style='width: ". $width. "px'" : "");
	$result.= ">". $val. "</div>";

	return $result;
}

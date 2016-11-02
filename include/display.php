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
else
	$d->query("select * from workout order by date desc, id");

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
	echo "<div class=\"descr date\">Kuupäev</div>";

	foreach ($workouts as $w)
		echo "<div class=\"descr ". $w->name. "\">". $w->title. "</div>";

	echo "<br/>";
}
else {
	if (!isset($results))
		return false;

	$date_width = "";

	if (isset($p->args[1])) {
		$column_widths = explode("-", $p->args[1]);
		$date_width = " style=\"width: ". array_shift($column_widths). "px\"";
	}

	foreach ($results as $date => $result) {
		$p->date = $date;

		list($yy, $mm, $dd) = explode("-", $date);
		$f_date = intval($dd). ". ". $months[intval($mm) - 1];

		echo "<div class=\"value\"". $date_width. ">". $f_date. "</div>";

		$count = 0;
		$width = false;

		foreach ($workouts as $w) {
			if (isset($column_widths))
				$width = $column_widths[$count++];

			echo results($p, $workouts_id[$w->id], $result, $width);
		}

		echo "<br clear=\"all\"/>";
	}
}

function results($p, $workout, $value, $width = false) {
	$val = "-";

	if (isset($value[$workout->id])) {
		//if (isset($value[$workout->id]["descr"]) && $value[$workout->id]["descr"])
			//$value[$workout->id]["type"] = "textarea";

		// what? investigate!

		//if ($value[$workout->id]["type"] == "textarea" && ($workout->id == 6 || $workout->id == 8))
			//$value[$workout->id]["type"] = "rounds_reps";

		switch ($workout->type) {//$value[$workout->id]["type"]
			case "rounds_reps":	$val = $value[$workout->id]["reps"]; break;
			case "reps":
			case "value":		$val = $value[$workout->id]["reps"]; break;
			case "textarea":	$val = $value[$workout->id]["descr"]; break;
			case "plank":		$val = $value[$workout->id]["reps"]; break;
		}
	}

	if (!$width)
		return $val;

	if ($val == "-")
		$result = "<div id=\"f_". $p->date. "_". $workout->id. "\" class=\"value ". $workout->name. " none\"". ($width ? " style=\"width: ". $width. "px\"" : ""). ">". $val. "</div>";
	else
		$result = "<div id=\"f_". $p->date. "_". $workout->id. "\" class=\"value ". $workout->name. "\"". ($width ? " style=\"width: ". $width. "px\"" : ""). ">". $val. "</div>";

	return $result;
}

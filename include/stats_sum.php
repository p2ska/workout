<?php

if (!isset($p->args[0]))
	return false;

$id = intval($p->args[0]);

$d->query("select * from workouts where id = ?", [ $id ]);

if (!$d->rows)
	return false;

$workout = $d->get_obj();

$total = 0;

if (empty($p->args[1]))
	$months = [ date("Y-m") ];
else
	$months = explode(":", $p->args[1]);

foreach ($months as $month) {
	$q = "select rounds, reps from workout where workout_id = ? && date like ? order by date";
	$v = [ $id, $month. "-%" ];

	$d->query($q, $v);

	foreach ($d->get_all() as $o) {
		if ($o->rounds > 0)
			$total += $o->rounds * $o->reps;
		else
			$total += $o->reps;
	}
}

echo $total;

if ($workout->weight) {
	if ($workout->unit)
		echo " ". $workout->unit. " ". $workout->weight. "kg (". weight_category($total * intval($workout->weight)). ")";
}
else {
	if ($workout->unit)
		echo " ". $workout->unit;
}

function weight_category($value) {
	if ($value >= 1000)
		return round_num($value / 1000, 1). " t";
	else
		return $value. " kg";
}

function round_num($num, $pl) {
	$rounded = round((float) $num, $pl);

	if ($rounded == ((int) $num) && $pl != 0)
		$rounded.= ".0";

	return $rounded;
}

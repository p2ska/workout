<?php

if (!isset($_POST["id"]))
	return false;

$workout = intval($_POST["id"]);

$o = $d->query("select * from workouts where id = ? limit 1", [ $workout ], true);

if (!isset($o->type))
	return false;

$last_value = get_last_value($d, $o);

echo "<input type='hidden' id='hidden'/>";

switch ($o->type) {
	case "rounds_reps":
		echo "<input type='hidden' id='descr' value=''/>";
		echo "<select id='rounds'>";
		echo "<option value=1>1</option>";
		echo "<option value=2>2</option>";
		echo "<option value=3 selected>3</option>";
		echo "<option value=4>4</option>";
		echo "<option value=5>5</option>";
		echo "<option value=6>6</option>";
		echo "</select> ";
		echo "<input type='text' id='reps' placeholder='". $last_value. "'/>";

		break;

	case "value":
		echo "<input type='hidden' id='descr' value=''/>";
		echo "<input type='hidden' id='rounds' value=1>";
		echo "<input type='text' id='reps' placeholder='". $last_value. "'/>";

		break;

	case "textarea":
		echo "<input type='hidden' id='rounds' value=1/>";
		echo "<input type='hidden' id='reps' value=1/>";
		echo "<textarea id='descr' placeholder='". $last_value. "'></textarea><br/>";

		break;

	default:
		break;
}

$workouts = $d->query("select max(id) as count from workouts", false, "count");

$next_workout = $workout + 1;

if ($next_workout > $workouts)
	$next_workout = 1;

$next = $d->query("select category from workouts where id = ?", [ $next_workout ], true);

function get_last_value($d, $workout) {
	if ($workout->type == "textarea")
		$d->query("select descr as value from workout where workout_id = ? && descr != ? order by date desc limit 1", [ $workout->id, "" ]);
	else
		$d->query("select reps as value from workout where workout_id = ? && reps != ? order by date desc limit 1", [ $workout->id, 0 ]);

	if ($d->rows)
		return $d->get_obj("value");
	else
		return false;
}

?>
<input
	type="submit"
	id="save"
	data-workout="<?php echo $workout; ?>"
	data-next-category="<?php echo $next->category; ?>"
	data-next-workout="<?php echo $next_workout; ?>"
	value="Lisa"
/>

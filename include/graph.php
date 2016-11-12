<?php

if (!isset($p->args[0]))
	return false;

$id = intval($p->args[0]);

$label = $data = [];

$d->query("select * from workouts");

foreach ($d->get_all() as $r)
	$workouts[$r->id] = $r;

if (!isset($workouts[$id]))
	return false;

$v = [ $id ];

if (isset($p->args[1]) && $p->args[1]) {
	if ($p->args[1] == "week")
		$start = time() - 7 * 86400;
	elseif ($p->args[1] == "month")
		$start = time() - 30 * 86400;
	else
		$start = 0;

	$q = "select * from workout where workout_id = ? && date >= ? order by date";

	$v[] = date("Y-m-d", $start);
}
else {
	$q = "select * from workout where workout_id = ? order by date";
}

$d->query($q, $v);

if (true) {
	$last_ts = false;

	foreach ($d->get_all() as $o) {
		list($yy, $mm, $dd) = explode("-", $o->date);

		$ts = mktime(0, 0, 0, $mm, $dd, $yy);

		if ($last_ts && $ts > ($last_ts + 86400)) {
			for ($a = $last_ts + 86400; $a < ($ts - 42200); $a += 86400) {
				$cd = date("Y-m-d", $a);

				$label[$cd] = date("d", $a);
				$data[$cd] = NULL;
			}
		}

		$label[$o->date] = $dd;

		if ($o->rounds)
			$data[$o->date] = $o->rounds * $o->reps;
		else
			$data[$o->date] = $o->reps;

		$last_ts = $ts;
	}
}
else {
	foreach ($d->get_all() as $o) {
		if ($o->reps == 0 && $o->rounds == 0 && $o->descr == "")
			continue;

		list($yy, $mm, $dd) = explode("-", $o->date);

		$label[$o->date] = $dd;

		if ($o->rounds)
			$data[$o->date] = $o->rounds * $o->reps;
		else
			$data[$o->date] = $o->reps;
	}
}

$color = hex_color($workouts[$id]->color);

$dataset = "{".
	"label: '". $workouts[$id]->title. "', ".
	"data: [ ". implode(", ", $data). " ], ".
	"backgroundColor: 'rgba(". $color. ", 0.2)', ".
	"borderColor: 'rgba(". $color. ", 1)', ".
	"borderWidth: 1".
	"}";

?>
<canvas id="chart" width="1000" height="200"></canvas>
<script>
	var ctx = $("#chart");
	var chart = new Chart(ctx, {
		type: "line",
		data: {
			labels: [ <?php echo implode(",", $label); ?> ],
			datasets: [ <?php echo $dataset; ?> ]
		},
		options: {
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: false
					}
				}]
			}
		}
	});
</script>

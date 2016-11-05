<?php

$d->query("select * from workouts");

foreach ($d->get_all() as $r)
	$workouts[$r->id] = $r;

$labels = $data = $workouts = $datasets = $args = [];

$q = substr(str_repeat(" || workout_id = ?", count($p->args)), 4);

foreach ($p->args as $key => $arg)
	$args[] = $arg;

$d->query("select min(date) as start, max(date) as end from workout limit 1");

$date = $d->get_obj();

list($sy, $sm, $sd) = explode("-", $date->start);
list($ey, $em, $ed) = explode("-", $date->end);

$start_ts = mktime(0, 0, 0, $sm, $sd, $sy);
$end_ts = mktime(0, 0, 0, $em, $ed, $ey);

$d->query("select * from workout where ". $q. " order by date", $args);

foreach ($d->get_all() as $o)
	$data[$o->date][$o->workout_id] = $o;

for ($a = $start_ts; $a < $end_ts; $a += 86400) {
	$date = date("Y-m-d", $a);
	$dt = substr($date, 8, 2);

	$labels[] = $dt;

	for ($b = 0; $b < count($p->args); $b++)
		if (isset($data[$date][$p->args[$b]])) {
			if ($data[$date][$p->args[$b]]->rounds)
				$vals[$date][$p->args[$b]] = $data[$date][$p->args[$b]]->rounds * $data[$date][$p->args[$b]]->reps;
			else
				$vals[$date][$p->args[$b]] = $data[$date][$p->args[$b]]->reps;
		}
		else {
			$vals[$date][$p->args[$b]] = null;
		}
}

/*

foreach ($d->get_all() as $r) {
	//$label[$r->workout_id][$r->date] = $dd;

	if ($r->rounds)
		$data[$r->workout_id][$r->date]	= $r->rounds * $r->reps;
	else
		$data[$r->workout_id][$r->date]	= $r->reps;

	if ($data[$r->workout_id][$r->date] == 13)
		$data[$r->workout_id][$r->date] = null;
}
*/

if (count($args) == 1)
	$bg = "backgroundColor: [ 'rgba(". hex_color($workouts[$r->workout_id]->color). ", 0.2)' ], ";
else
	$bg = "";

/*
foreach ($vals as $date => $values) {
	foreach ($values as $key => $value) {
		$color = hex_color($workouts[$key]->color);

		$datasets[$
		foreach ($value as $cd => $val) {
			list($yy, $mm, $dd) = explode("-", $cd);
			$labels[$cd] = $dd;
		}
	}

	//$datasets[] = "{ label: '". $workouts[$key]->title. "', data: [ ". implode(", ", $data[$key]). " ], ".
		//$bg. "borderColor: [ 'rgba(". $color. ", 1)' ], borderWidth: 1 }";
}
*/

?>
<canvas id="chart" width="1000" height="100"></canvas>
<script>
var ctx = $("#chart");
var chart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [ <?php echo implode(",", $labels); ?> ],
        datasets: [ <?php echo implode(",", $datasets); ?> ]
    },
    options: {
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

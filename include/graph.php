<?php

if (!isset($p->args[0]))
	return false;

$id = $p->args[0];
$label = $data = [];

$d->query("select * from workouts");

foreach ($d->get_all() as $r)
	$workouts[$r->id] = $r;

$d->query("select * from workout where workout_id = ? order by date", [ $id ]);

foreach ($d->get_all() as $o) {
	list($yy, $mm, $dd) = explode("-", $o->date);

	$label[$o->date] = $dd;

	if ($o->rounds)
		$data[$o->date] = $o->rounds * $o->reps;
	else
		$data[$o->date] = $o->reps;
}

$color = hex_color($workouts[$id]->color);
$bg_color = "backgroundColor: [ 'rgba(". $color. ", 0.2)' ], ";

$dataset = "{ label: '". $workouts[$id]->title. "', data: [ ". implode(", ", $data). " ], ".
	$bg_color. "borderColor: [ 'rgba(". $color. ", 1)' ], borderWidth: 1 }";

?>
<canvas id="chart" width="1000" height="100"></canvas>
<script>
var ctx = $("#chart");
var chart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [ <?php echo implode(",", $label); ?> ],
        datasets: [ <?php echo $dataset; ?> ]
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

<?php

$d->query("select * from workout where workout_id = ? order by id", [ 13 ]);

$labels = $data = [];

foreach ($d->get_all() as $r) {
	list($yy, $mm, $dd) = explode("-", $r->date);

	$labels[]	= $dd;
	$data[]		= $r->reps;
}

?>
<canvas id="myChart" width="1000" height="100"></canvas>
<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [ <?php echo implode(",", $labels); ?> ],
        datasets: [{
            label: 'Kaal',
            data: [ <?php echo implode(", ", $data); ?> ],
            backgroundColor: [
                'rgba(0, 153, 255, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.3)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(0, 153, 255, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255,99,132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
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

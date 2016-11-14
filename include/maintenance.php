<?php

$today = time();

for ($day = 30; $day >= 0; $day--) {
    $ct = date("Y-m-d", $today - $day * 86400);

	$d->query("select id from workout where date = ? limit 1", [ $ct ]);

	if (!$d->rows) {
		$d->query(
			"insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
			[ $ct, 13, 0, 0, "", date("Y-m-d H:i:s") ]
		);
	}
}

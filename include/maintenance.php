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

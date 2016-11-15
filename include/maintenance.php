<?php

$current_ts = mktime(12, 0, 0, date("m"), date("d"), date("Y"));

$last_entry = $d->query("select date from workout order by date desc limit 1", false, "date");

if (!$last_entry)
    $last_entry = date("Y-m-d", time() - 6 * 86400);

list($yy, $mm, $dd) = explode("-", $last_entry);

$last_ts = mktime(12, 0, 0, $mm, $dd, $yy) + 86400;

if ($last_ts && $last_ts <= $current_ts) {
	for ($a = $last_ts; $a <= $current_ts; $a += 86400)
        $d->query(
            "insert into workout (date, workout_id, rounds, reps, descr, added) values (?, ?, ?, ?, ?, ?)",
            [ date("Y-m-d", $a), 13, 0, 0, "", date("Y-m-d H:i:s") ]
        );
}

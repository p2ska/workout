<?php

$km = chain_wear($d, $p->args[0]);

if ($km >= 1500)
    $km = "<span style='color: #f00; text-decoration: underline'>". $km. "</span>";
elseif ($km == 0)
    $km = "?";

echo $p->args[0]. " ketivahetusest: ". $km. " km";

//move_workout($d, 11, 7);

function chain_wear($d, $bike_name) {
    $km = false;

    $bike = $d->query("select id from workouts where name = ? limit 1", [ "bike_". $bike_name ], true);
    $replaced = $d->query("select date from workout where workout_id = ? && descr like ? order by id desc limit 1", [ 12, "%ketivahetus:". $bike_name. "%" ], true);

    if (!empty($replaced)) {
        $riden = $d->query("select sum(reps) as total from workout where workout_id = ? && date >= ?", [ $bike[0]->id, $replaced[0]->date ], true);

        if (!empty($riden))
            $km = $riden[0]->total;
    }

    return $km;
}
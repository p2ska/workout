<?php

// vaata, mis perioodi puhul on andmed olemas

$d->query("select substr(date, 1, 7) as date from workout group by substr(date, 1, 7) order by date");

// kuva aastad ja kuud

$cy = false;

// millised on aktiived kuud?

if (empty($p->args[0]))
	$selected[date("Y-m")] = true;
else {
	$selected = [];
	$dates = explode(":", $p->args[0]);

	foreach ($dates as $date)
		$selected[$date] = true;
}

foreach ($d->get_all() as $o) {
	list($y, $m) = explode("-", $o->date);

	if (!$cy || $cy != $y) {
		if ($cy)
			echo "</div>";

		echo "<div id='period_". $y. "'>";
		echo "<span id='year_". $y. "' class='period year' data-year='". $y. "' data-month=''>". $y. "</span>";
	}

	if (isset($selected[$y. "-". $m]))
		$active = " selected";
	else
		$active = "";

	echo "<span class='period month". $active. "' data-year='". $y. "' data-month='". $m. "'>". $m. "</span>";

	$cy = $y;
}

echo "</div>";

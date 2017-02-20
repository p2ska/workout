<?php

// vaata, mis perioodi puhul on andmed olemas

$d->query("select substr(date, 1, 7) as date from workout group by substr(date, 1, 7) order by date");

// kuva aastad ja kuud

$cy = false;

foreach ($d->get_all() as $o) {
	list($y, $m) = explode("-", $o->date);

	if (!$cy || $cy != $y) {
		if ($cy)
			echo "</div>";

		echo "<div id='period_". $y. "'>";
		echo "<span id='year_". $y. "' class='period year' data-year='". $y. "' data-month=''>". $y. "</span>";
	}

	echo "<span class='period month' data-year='". $y. "' data-month='". $m. "'>". $m. "</span>";

	$cy = $y;
}

echo "</div>";

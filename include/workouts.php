<?php

if (!isset($_POST["id"]))
	return false;

//require ROOT. "/include/import.php";

$first_cat = false;
$category = strtolower(substr($_POST["id"], 0, 2));

$d->query("select * from workouts where category = ? order by sort", [ $category ]);

echo " :: ";

foreach ($d->get_all() as $o) {
	if (!$first_cat) {
		$first_cat = $o->id;

		echo "<span id=\"workout_". $o->id. "\" class=\"workout active\" data-id=\"". $o->id. "\">". $o->title. "</span> :: ";
	}
	else {
		echo "<span id=\"workout_". $o->id. "\" class=\"workout\" data-id=\"". $o->id. "\">". $o->title. "</span> :: ";
	}
}

?>
<hr style="margin-top: 10px"/>
<div id="workout"></div>
<script>
	$("#workout_<?php echo $first_cat; ?>").trigger("click");
</script>

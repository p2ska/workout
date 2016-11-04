<h1><?php echo date("j"). ". ". $months[date("n") - 1]; ?></h1>
<input type="hidden" id="date" value="<?php echo date("Y-m-d"); ?>">
<hr/>
<div class="categorys">
	:: <span id="w1" class="category w1">Kaal</span> ::
	<span id="k1" class="category k1">Kätekõverdused</span> ::
	<span id="r1" class="category r1">Ratas</span> ::
	::&nbsp;<span id="s1" class="category s1">Jõutreening 1</span> ::
	<span id="s2" class="category s2">Jõutreening 2</span> ::
	<span id="p1" class="category p1">Plank</span> ::
</div>
<hr/>
<div id="workouts"></div>
<script>
	$("#date").datepicker({
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		onSelect: function() {
			var ex = $("#date").val().split("-");
			var picked_date = parseInt(ex[2]) + ". " + months[parseInt(ex[1]) - 1];

			$("#input > h1").html(picked_date);
		}
	});

	$("h1").click(function() {
		$("#date").datepicker("show");
	});

	$("#w1").trigger("click");
</script>

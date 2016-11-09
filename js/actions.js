var current_graph	= false,
	current_period	= false,
	next_workout	= false,
	results_width	= false,
	column_widths	= false;

var months = [ "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember" ];

$("#input").on("click", ".category", function() {
	var that = $(this);
	var workout = that.prop("id");

	$(".category").removeClass("underline")
	$("#workouts").load("=workouts", { id: workout });

	that.addClass("underline");
});

$("#input").on("click", ".workout", function() {
	var id = $(this).data("id");

	$("#workout").load("=input", { id: id });

	$(".workout").removeClass("active");
	$("#workout_" + id).addClass("active");

	setTimeout(function() { $("input[type='text'], textarea").focus(); }, 100);
});

$("#period").on("click", ".period", function() {
	current_graph = $("#results .active").data("workout");
	current_period = $(this).data("length");

	$("#period .period").removeClass("active");
	$(this).addClass("active");

	$("#graph").load("=graph/" + current_graph + "/" + current_period);

	bake_cookie("period", current_period, 30);
});

$("#results").on("click", ".descr:not(.date, .food, .route)", function() {
	current_graph = $(this).data("workout");

 	if ($(this).hasClass("active")) {
		$(this).removeClass("active");

		$("#graph").html("");

		eat_cookie("graph");
	}
	else {
		$(".descr").removeClass("active");
		$(this).addClass("active");

		$("#graph").load("=graph/" + current_graph + "/" + current_period);

		bake_cookie("graph", current_graph, 30);
	}
});

$("#results").on("click", ".value", function() {
	var cell_width = $(this).width();
	var cell_height = $(this).height() + 6;
	var cell_value = $(this).html();
	var cell_status = cell_value.substring(0, 6);

	if (cell_status != "&nbsp;") {
		if ($(this).hasClass("food"))
			$(this).html("&nbsp;<textarea class='edit_cell' style='width: " + cell_width + "px; height: " + (cell_height * 3) + "px;'>" + cell_value + "</textarea>");
		else
			$(this).html("&nbsp;<input type='text' class='edit_cell' style='width: " + cell_width + "px; height: " + cell_height + "px' value='" + cell_value + "'/>");

		setTimeout(function() { $(".edit_cell").focus().val(cell_value); }, 100);
	}
});

$("#results").on("keydown", ".edit_cell", function(e) {
	var key = e.keyCode || e.which;

	if (key == 9) {
		e.preventDefault();

		next_workout = true;

		$(":focus").blur();
	}
	else if (key == 13) {
		$(":focus").blur();
	}
});

$("#results").on("focusout", ".edit_cell", function(e) {
	var parent = $(this).parent();
	var value = $(this).val();
	var cell_id = parent.prop("id");

	$.post("=save", {
		cell_id:	cell_id,
		value: 		value
	}).done(function(result) {
		if (result != "NOK") {
			parent.html(result);
			parent.removeClass("none");

			var c = parent.html();

			if (c == "-" || c == "" || c == 0)
				parent.addClass("none")

			if (next_workout) {
				parent.next().trigger("click");

				next_workout = false;
			}

			$("#graph").load("=graph/" + current_graph + "/" + current_period);
		}
		else {
			alert("FAILURE");
		}
	});
});

$("#input").keypress(function(e) {
	if (e.which == 13) {
		var next_category = $("#save").data("next-category");
		var next_workout = $("#save").data("next-workout");

		$("#save").trigger("click");

		setTimeout(function() { $("#" + next_category).trigger("click"); }, 50);
		setTimeout(function() { $("#workout_" + next_workout).trigger("click"); }, 100);
	}
});

$("#input").on("click", "#save", function() {
	var workout = $(this).data("workout");
	var date = $("#date").val();

	$.post("=save", {
		id: 	workout,
		date:	date,
		rounds:	$("#rounds").val(),
		reps:	$("#reps").val(),
		descr:	$("#descr").val()
	}).done(function(result) {
		if (result == "OK") {
			var next_category = $("#save").data("next-category");
			var next_workout = $("#save").data("next-workout");

			$("#f_" + date + "_" + workout).fadeOut(50).load("=display/element/" + workout + "/" + date).fadeIn(100);

			setTimeout(function() { $("#" + next_category).trigger("click"); }, 50);
			setTimeout(function() { $("#workout_" + next_workout).trigger("click"); }, 100);

			$("#graph").load("=graph/" + current_graph + "/" + current_period);
		}
		else {
			alert("FAILURE");
		}
	});
});

$(document).ready(function() {
	current_graph = parseInt(fetch_cookie("graph"));
	current_period = fetch_cookie("period");

	if (current_period != "week" && current_period != "month")
		current_period = "year";

	$("#input").load("=workout");

	$("#results_header").load("=display/header", function() {
		$("#results_body").load("=display/body/" + get_column_widths());
		$("#results").css("min-width", results_width);

		$("#graph").load("=graph/" + current_graph + "/" + current_period);

		if (current_graph)
			$("#w_" + current_graph).addClass("active");

		$("#period_" + current_period).addClass("active");
	});
});

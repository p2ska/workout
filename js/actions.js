var current_graph	= false,
	current_period	= false,
	results_width	= false,
	column_widths	= false;

var months = [ "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember" ];

/*
$("#input").on("click", ".category", function() {
	var that = $(this);
	var category = that.prop("id");

	$(".category").removeClass("underline")
	$("#workouts").load("=workouts", { id: category });

	that.addClass("underline");
});
*/

/*
$("#input").on("click", ".workout", function() {
	var id = $(this).data("id");

	$("#workout").load("=input/" + id);

	$(".workout").removeClass("active");
	$("#workout_" + id).addClass("active");

	setTimeout(function() { $("input[type='text'], textarea").focus(); }, 100);
});
*/

$("#period").on("click", ".period", function() {
	var cp = $(this);

	var ct = cp.hasClass("year");
	var cy = cp.data("year");
	var cm = cp.data("month");

	if (ct) {
		if (cp.hasClass("selected"))
			$("#period_" + cy + " > .period").removeClass("selected");
		else
			$("#period_" + cy + " > .period").addClass("selected");
	}
	else {
		if (cp.hasClass("selected")) {
			if ($("#year_" + cy).hasClass("selected")) {
				$("#period_" + cy + " > .period").removeClass("selected");

				cp.addClass("selected");
			}
			else {
				cp.removeClass("selected");
			}
		}
		else {
			cp.addClass("selected");
		}
	}

	current_period = "";

	$("#stats_period .month").each(function() {
		if ($(this).hasClass("selected"))
			current_period = current_period + ":" + $(this).data("year") + "-" + $(this).data("month");
	});

	current_period = current_period.substr(1);

	$("#graph").load("=graph/" + current_graph + "/" + current_period);
	$("#stats_sum").load("=stats_sum/" + current_graph + "/" + current_period);

	bake_cookie("period", current_period, 30);
});

$("#results").on("click", ".descr:not(.date, .food, .route)", function() {
	current_graph = $(this).data("workout");

 	if ($(this).hasClass("active")) {
		/*
		$(this).removeClass("active");

		$("#graph").html("");

		eat_cookie("graph");
		*/
	}
	else {
		$(".descr").removeClass("active");
		$(this).addClass("active");

		$("#graph").load("=graph/" + current_graph + "/" + current_period);
		$("#stats_sum").load("=stats_sum/" + current_graph + "/" + current_period);

		bake_cookie("graph", current_graph, 30);
	}
});

$("#results").on("click", ".value", function() {
	var parent 		= $(this).parent();
	var cell_width	= $(this).innerWidth() - 16;
	var cell_height = $(this).innerHeight() - 2;
	var cell_value	= $(this).html();
	var cell_status = cell_value.substring(0, 6);

	$(this).css("overflow", "visible");

	if (cell_value == "-")
		cell_value = "";

	if (cell_status != "&nbsp;") {
		if ($(this).hasClass("food") || $(this).hasClass("route"))
			$(this).html("&nbsp;<textarea class='edit_cell' style='width: " + cell_width + "px; height: " + (cell_height * 5 - 10) + "px;'>" + cell_value + "</textarea>");
		else
			$(this).html("&nbsp;<input type='text' class='edit_cell' style='width: " + cell_width + "px; height: " + cell_height + "px' value='" + cell_value + "'/>");

		setTimeout(function() { $(".edit_cell").focus().val(cell_value); }, 100);
	}
});

$("#results").on("keydown", ".edit_cell", function(e) {
	var keycode = e.keyCode || e.which;

	if (keycode == 9 || keycode == 13 || keycode == 27) {
		e.preventDefault();

		$(this).data("keycode", keycode);
		$(":focus").blur();
	}
});

$("#results").on("focusout", ".edit_cell", function(e) {
	var parent		= $(this).parent();
	var value		= $(this).val();
	var keycode		= $(this).data("keycode");
	var cell_id		= parent.prop("id");
	var date 		= cell_id.substring(2, 12);
	var workout		= cell_id.substring(13);
	var next_workout= false;

	parent.css("overflow", "hidden");

	if (keycode == 27) {
		parent.load("=display/element/" + workout + "/" + date);

		return false;
	}
	else if (keycode == 9)
		next_workout = true;

	$.post("=save", {
		date:		date,
		workout:	workout,
		value: 		value
	}).done(function(result) {
		if (result != "NOK") {
			parent.html(result);
			parent.removeClass("none");

			var c = parent.html();

			if (c == "-" || c == "" || c == 0)
				parent.addClass("none")

			if (parent.hasClass("suggestion_strong"))
				parent.removeClass("suggestion_strong");
			else if (parent.hasClass("suggestion_normal"))
				parent.removeClass("suggestion_normal");
			else if (parent.hasClass("suggestion_mild"))
				parent.removeClass("suggestion_mild");

			if (next_workout)
				parent.next().trigger("click");

			if (workout == current_graph)
				$("#graph").load("=graph/" + current_graph + "/" + current_period);
		}
		else {
			alert("FAILURE");
		}
	});
});

/*
$("#input").keypress(function(e) {
	if (e.which == 13) {
		var next_category = $("#save").data("next-category");
		var next_workout = $("#save").data("next-workout");

		$("#save").trigger("click");

		setTimeout(function() { $("#" + next_category).trigger("click"); }, 50);
		setTimeout(function() { $("#workout_" + next_workout).trigger("click"); }, 100);
	}
});
*/

/*
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

			if (workout == current_graph)
				$("#graph").load("=graph/" + current_graph + "/" + current_period);
		}
		else {
			alert("FAILURE");
		}
	});
});
*/

$(document).ready(function() {
	current_graph = parseInt(fetch_cookie("graph"));
	current_period = fetch_cookie("period");

	$("#debug").load("=maintenance");
	/* $("#input").load("=workout"); */
	$("#stats_period").load("=stats_period/" + current_period);

	$("#results_header").load("=display/header", function() {
		$("#results_body").load("=display/body/" + get_column_widths());
		$("#results").css("min-width", results_width);

		$("#graph").load("=graph/" + current_graph + "/" + current_period);
		$("#stats_sum").load("=stats_sum/" + current_graph + "/" + current_period);

		if (current_graph)
			$("#w_" + current_graph).addClass("active");

		//$("#period_" + current_period).addClass("active");
	});
});

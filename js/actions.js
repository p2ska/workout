var results_width = false,
	column_widths = false,
	next_workout = false;

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
	var period = $(this).data("length");
	var workout = $("#results .active").data("workout");

	$("#period .period").removeClass("active");
	$(this).addClass("active");

	$("#graph").load("=graph/" + workout + "/" + period);

	bake_cookie("period", period, 30);
});

$("#results").on("click", ".descr:not(.date, .food, .route)", function() {
	var workout = $(this).data("workout");

 	if ($(this).hasClass("active")) {
		$(this).removeClass("active");
		$("#graph").html("");

		eat_cookie("chart");
	}
	else {
		$(".descr").removeClass("active");
		$(this).addClass("active");

		$("#graph").load("=graph/" + workout);

		bake_cookie("chart", workout, 30);
	}
});

$("#results").on("click", ".value", function() {
	var cell_width = $(this).width();
	var cell_height = $(this).height() + 6;
	var cell_value = $(this).html();
	var cell_status = cell_value.substring(0, 6);

	if (cell_status != "&nbsp;") {
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
	var cell_id = parent.prop("id");
	var value = $(this).val();

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
				$(parent).next().trigger("click");

				next_workout = false;
			}
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
		}
		else {
			alert("FAILURE");
		}
	});
});

$(document).ready(function() {
	var chart = fetch_cookie("chart");
	var period = fetch_cookie("period");

	if (period != "week" && period != "month")
		period = "year";

	$("#input").load("=workout");

	$("#results_header").load("=display/header", function() {
		$("#results_body").load("=display/body/" + get_column_widths());

		$("#graph").load("=graph/" + chart + "/" + period);
		$("#results").css("min-width", results_width);

		if (chart)
			$("#w_" + chart).addClass("active");

		$("#period_" + period).addClass("active");
	});
});

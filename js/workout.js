var results_width = false,
	column_widths = false;

var months = [ "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember" ];

function header_magic() {
	var a, b, c;

	for (a = 0; a < 1000; a++) {
		b = "#" + (0x1000000 + (Math.random()) * 0xffffff).toString(16).substr(1, 6);
		c = Math.random() * 30;

		$("#header").append("<div id='cell_" + a + "'></div>");
		$("#cell_" + a).css("background-color", b).css("width", c);
	}
}

function get_column_widths() {
	var w, columns = [];

	results_width = 0;

	$("#results_header .descr").each(function() {
		w = parseInt($(this).width());

		if ($(this).width() != w)
			$(this).width(w);

		columns.push(w);
		results_width += w;
	});

	results_width += 20 + 18 * 22; // padding+cols*(padding+margin+border)

	column_widths = columns.join("-");

	return column_widths;
}

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

	setTimeout(function() { $('input[type="text"],textarea').focus(); }, 100);
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

$("#results").on("keypress", ".edit_cell", function(e) {
	if (e.which == 13)
		$(":focus").blur();
});

$("#results").on("focusout", ".edit_cell", function() {
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

			if (parent.html() == "-")
				parent.addClass("none")
		}
		else {
			alert("FAILED");
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

			/*$("#workout").html("");*/
			$("#f_" + date + "_" + workout).fadeOut(50).load("=display/element/" + workout + "/" + date).fadeIn(100);

			setTimeout(function() { $("#" + next_category).trigger("click"); }, 50);
			setTimeout(function() { $("#workout_" + next_workout).trigger("click"); }, 100);
		}
		else {
			alert("FAILED");
		}
	});
});

$.fn.glowEffect = function(start, end, duration) {
    var that = this;

    return this.css("a", start).animate({ a: end }, {
		duration: duration,
        step: function(now) {
            that.css("text-shadow", "0px 0px " + now + "px #ff0");
        }
    });
};

$(document).ready(function() {
	$("#input").load("=workout");
	$("#results_header").load("=display/header", function() {
		$("#results_body").load("=display/body/" + get_column_widths());
		$("#graph").load("=graph");
		$("#results").css("min-width", results_width);
	});
});

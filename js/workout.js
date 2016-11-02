var column_widths = false;
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

	$("#results_header .descr").each(function() {
		w = parseInt($(this).width());

		if ($(this).width() != w)
			$(this).width(w);

		columns.push(w);
	});

	column_widths = columns.join("-");

	return column_widths;
}

$("#input").on("click", ".category", function() {
	$("#workouts").load("=workouts", { id: $(this).prop("id") });
});

$("#input").on("click", ".workout", function() {
	var id = $(this).data("id");
	$("#workout").load("=workout", { id: id });
	$(".workout").removeClass("active");
	$("#workout_" + id).addClass("active");
});

$("#input").on("click", "#save", function() {
	var id = $(this).data("id");
	var date = $("#date").val();

	$.post("=save", {
		id: 	id,
		date:	date,
		rounds:	$("#rounds").val(),
		reps:	$("#reps").val(),
		descr:	$("#descr").val()
	}).done(function(result) {
		if (result == "OK") {
			$("#workout").html("");
			$("#f_" + date + "_" + id).load("=display/element/" + id + "/" + date);
		}
		else {
			alert("FAILED");
		}
	});
});

$(document).ready(function() {
	header_magic();

	$("#input").load("=input");
	$("#results_header").load("=display/header", function() {
		$("#results_body").load("=display/body/" + get_column_widths());
	});
});

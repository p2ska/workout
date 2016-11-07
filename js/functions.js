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

	results_width += 40 + 18 * 22; // padding+cols*(padding+margin+border)

	column_widths = columns.join("-");

	return column_widths;
}

function bake_cookie(name, value, days) {
    if (days) {
        var date = new Date();

		date.setTime(date.getTime() + (days * 86400000));

        var expires = "; expires=" + date.toGMTString();
    }
    else {
		var expires = "";
	}

    document.cookie = name + "=" + value + expires + "; path=/";
}

function fetch_cookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");

    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];

        while (c.charAt(0) == " ")
			c = c.substring(1, c.length);

		if (c.indexOf(nameEQ) == 0)
			return c.substring(nameEQ.length, c.length);
    }

    return null;
}

function eat_cookie(name) {
    bake_cookie(name, "", -1);
}

$.fn.glowEffect = function(start, end, duration) {
    var that = this;

    return this.css("a", start).animate({ a: end }, {
		duration: duration,
        step: function(now) {
            that.css("text-shadow", "0px 0px " + now + "px #ff0");
        }
    });
};

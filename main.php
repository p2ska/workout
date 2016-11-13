<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="fonts/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/general.css" />
<link rel="stylesheet" type="text/css" href="css/input.css" />
<link rel="stylesheet" type="text/css" href="css/results.css" />
<title>Work\o/ut</title>
</head>
<body>
<!--<div id="input"></div>-->
<div id="progress">
	<span id="workout_w1" class="next_workout" data-category="w1">sangpomm</span>
	<span id="workout_w2" class="next_workout" data-category="w2">hantel</span>
</div>
<div id="period">
	<span id="period_week" class="period" data-length="week">nädal</span>
	<span id="period_month" class="period" data-length="month">kuu</span>
	<span id="period_year" class="period" data-length="year">aasta</span>
</div>
<div id="graph"></div>
<div id="results">
	<div id="results_header"></div>
	<div id="results_body"></div>
</div>
<div id="debug"></div>
</body>
<script type="text/javascript" src="js/jquery/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui-1.12.1.min-localized.js"></script>
<script type="text/javascript" src="js/chart/chart.min.js"></script>
<script type="text/javascript" src="js/functions.js?<?php echo time(); ?>"></script>
<script type="text/javascript" src="js/actions.js?<?php echo time(); ?>"></script>
</html>

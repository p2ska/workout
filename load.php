<?php

if (!isset($_GET["args"]))
	return false;

define("VALID_REF", true);

require_once "include/_init.php";

$raw_args = $_GET["args"];

$p->args = explode("/", str_replace(":", "/", $raw_args));
$p->page = array_shift($p->args);

if ($p->args) {
	foreach ($p->args as $key => $val)
		if (!$val)
			unset($p->args[$key]);
}

if (file_exists(ROOT. "/include/". $p->page. ".php")) {
	require_once ROOT. "/include/". $p->page. ".php";
}

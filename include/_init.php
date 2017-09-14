<?php

defined("VALID_REF") or die();

// PATHS

define("ROOT",				"c:/xampp/htdocs/workout");
define("DB_CONNECTOR",		"c:/xampp/security/workout/_connector.php");

define("PAGE_SIZE",			20);

// VARIABLES

$months = [ "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember" ];
$weekdays = [ "P", "E", "T", "K", "N", "R", "L" ];

// INCLUDES

require_once DB_CONNECTOR;
require_once ROOT. "/classes/db.php";

// CLASSES

$p = new stdClass();
$d = new DATABASE();

// INIT

$p->page = $p->args = "";

$d->connect(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET, DB_COLLATION);

// FUNCTIONS

function compare_strings($str1, $str2, $encoding = false) {
    if (!$encoding)
        $encoding = mb_internal_encoding();

    if (!is_array($str2))
        $str2 = [ $str2];

    foreach ($str2 as $str) {
        if (strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str, $encoding)) == 0)
            return true;
    }

    return false;
}

function clean_input($input, $in_length = 255, $out_length = 255) {
    $result = "";
	$allowed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890;:+-_,. öäüõšžÖÄÜÕŠŽ";

	$input = substr($input, 0, $in_length);

	for ($a = 0; $a < strlen($input); $a++)
		if (strpos($allowed, $input[$a]) !== false)
			$result .= $input[$a];

    return substr($result, 0, $out_length);
}

function hex_color($hex) {
	return hexdec(substr($hex, 1, 2)). ", ". hexdec(substr($hex, 3, 2)). ", ". hexdec(substr($hex, 5, 2));
}

function get_dump($var) {
    ob_start();

    print_r($var);

    return ob_get_clean();
}

function p_log($file, $str, $append = true, $print_date = true) {
    $path = ROOT. "/logs/";

    $fp = fopen($path . $file, $append ? "a" : "w");

	if ($print_date) {
		fputs($fp, date("d.m.Y H:i:s"). "\n");
		fputs($fp, "--------------------------------------------------\n");
	}

	fputs($fp, get_dump($str). "\n");

	if ($print_date)
		fputs($fp, "--------------------------------------------------\n");

    fclose($fp);
}

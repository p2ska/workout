<?php

defined("VALID_REF") or die();

// PATHS

define("ROOT",			"c:/xampp/htdocs/workout");
define("DB_CONNECTOR",	"c:/xampp/security/workout/_connector.php");

// VARIABLES

$months = [ "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember" ];

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

function get_dump($var) {
    ob_start();

    print_r($var);

    return ob_get_clean();
}

function p_log($file, $str, $append = false) {
    $path = ROOT. "/logs/";

    $fp = fopen($path . $file, $append ? "a" : "w");

	fputs($fp, date("d.m.Y H:i:s"). "\n");
	fputs($fp, "--------------------------------------------------\n");
	fputs($fp, get_dump($str). "\n");
	fputs($fp, "--------------------------------------------------\n");

    fclose($fp);
}

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

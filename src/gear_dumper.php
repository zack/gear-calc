<?php

$COLUMNS = [
    "Name",
    "Slot",
    "ARM",
    "STA",
    "AGI",
    "STR",
    "HIT",
    "CRI",
    "HST",
    "DGE",
    "ATP",
    "DEF",
    "FRE",
];

if (count($argv) != 3) {
    print("Usage: php gear_dumper.php database gear\n");
    die;
}

$db_file = $argv[1];
$gear_file = $argv[2];

$db = new SQLite3($db_file);
$file_handle = fopen($gear_file, "r");

if ($file_handle) {
    while (($line = fgets($file_handle)) !== false) {
        $arr = explode(",", $line);
        $clean_arr = array_map(function($val) {
            return is_numeric($val) ? intval($val) : "\"$val\"";
        }, $arr);

        $line = "INSERT INTO Item (" .  join($COLUMNS, ",") . ") VALUES (" . join($clean_arr, ",") .")";
        $db->exec($line);

        // var_dump($line);
    }

    fclose($file_handle);
} else {
    print "File Error.\n";
}

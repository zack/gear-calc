<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once 'consts.php';
require_once 'db.php';
require_once 'gearset.php';
require_once 'item.php';

use function BenTools\CartesianProduct\cartesian_product;

function getAllItems($db) {
    $items = [];
    foreach(TYPES as $type) {
        $items[$type] = $db->getItemsOfType($type);
    }
    return $items;
}

function getAllGearCombinationsByType($gear) {
    $items = [];

    foreach(TYPES as $type) {
        $items[$type] = getGearCombinationsForType($gear[$type]);
    }

    return $items;
}

function getGearCombinationsForType($gear) {
    $slot = $gear[0]->getSlot();
    $item_count = COUNT_BY_SLOT[$slot];
    $result = [];

    if ($item_count === 1) {
        foreach($gear as $x_key => $x_value) {
            $result[] = [(object)$x_value];
        };
    }  else if ($item_count === 2) {
        foreach($gear as $x_key => $x_value) {
            foreach(array_slice($gear, $x_key + 1) as $y_key => $y_value) {
                $result[] = [(object)$x_value, (object)$y_value];
            }
        };
    } else {
        throw new Exception("Invalid gear combinations: Choose {$item_count} for {$slot}.");
    }

    return $result;
}

function rightPad($len, $string, $spacer = " ") {
    $pad = str_repeat(" ", $len - strlen($string));
    return "{$string}{$pad}";
}

function leftPad($len, $string, $spacer = " ") {
    $pad = str_repeat($spacer, $len - strlen($string));
    return "{$pad}{$string}";
}

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

$db = new DB('db.sqlite3');

$all_gear = getAllItems($db);
$all_type_combinations = getAllGearCombinationsByType($all_gear);
$all_gear_combinations = cartesian_product($all_type_combinations)->asArray();

$count = count($all_gear_combinations);
$text = "\nPersisting combination {$count} gearsets!\n";

$start_time = time();
$total_minutes = "";
$total_seconds_remainder = "";

foreach ($all_gear_combinations as $idx => $gear_combination) {
    $new_set = new GearSet(flatten($gear_combination));

    if ($idx % 10000 === 0 && $idx > 0) {
        $seconds_per_thousand = time() - $start_time;
        $total_seconds = $count / $idx * $seconds_per_thousand;
        $total_minutes = floor($total_seconds / 60);
        $total_seconds_remainder = leftPad(2, $total_seconds % 60, "0");
    }

    $elapsed_minutes = leftPad(2, floor((time() - $start_time) / 60), "0");
    $elapsed_seconds_remainder = leftPad(2, (time() - $start_time) % 60, "0");

    $percent = number_format($idx/$count*100, 3);
    $bar = str_repeat("\u{2588}", round($percent / 100 * 50));
    $space = str_repeat(" ", 50 - round($percent / 100 * 50));
    $padded_idx = leftPad(strlen("{$count}"), "${idx}", "0");
    $time = "{$elapsed_minutes}:{$elapsed_seconds_remainder}";
    if ($total_minutes !== "") {
        $time .= "/{$total_minutes}:{$total_seconds_remainder}";
    }

    $text = "\r[{$bar}${space}] | {$padded_idx}/{$count} | ({$percent}%) | {$time} ";

    print($text);

    if ($idx % 1000 === 0 && $idx > 0) {
        $db->commitTransaction();
    }

    if ($idx % 1000 === 0) {
        $db->beginTransaction();
    }

    $new_set->persistSet($db);
}
// $a_gearset = new GearSet(flatten($all_gear_combinations[0]));
// $a_gearset->printSet();
// $a_gearset->printStats();
// $b_gearset = GearSet::parseSetString($db, $a_gearset->getSetString());
// $b_gearset->printStats();
// $a_gearset->persistSet($db);

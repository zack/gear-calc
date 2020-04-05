<?php

require_once '../vendor/autoload.php';

require_once 'consts.php';
require_once 'util.php';

require_once 'db.php';
require_once 'gearset.php';
require_once 'item.php';
require_once 'progressbar.php';

use function BenTools\CartesianProduct\cartesian_product;

ini_set('memory_limit', '5G');

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

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

$db = new DB('../db.sqlite3');

$all_gear = getAllItems($db);
$all_type_combinations = getAllGearCombinationsByType($all_gear);
$all_gear_combinations = cartesian_product($all_type_combinations)->asArray();

$total_combinations = count($all_gear_combinations);
$text = "\nPersisting combination {$total_combinations} gearsets!\n";

$progressBar = new ProgressBar($total_combinations);

$db->beginTransaction();
foreach ($all_gear_combinations as $idx => $gear_combination) {
    $new_set = new GearSet(flatten($gear_combination), $db);
    $progressBar->display($idx);
    $new_set->persistSet($db);
}
$db->commitTransaction();

<?php

require_once __DIR__ . '/vendor/autoload.php';

use function BenTools\CartesianProduct\cartesian_product;

const HEAD = 'HEAD';
const NECK = 'NECK';
const SHOULDER = 'SHOULDER';
const BACK = 'BACK';
const CHEST = 'CHEST';
const WRIST = 'WRIST';
const HANDS = 'HANDS';
const WAIST = 'WAIST';
const LEGS = 'LEGS';
const FEET = 'FEET';
const FINGER = 'FINGER';
const TRINKET = 'TRINKET';
const WEAPON = 'WEAPON';

const SLOTS = [
    HEAD,
    NECK,
    SHOULDER,
    BACK,
    CHEST,
    WRIST,
    HANDS,
    WAIST,
    LEGS,
    FEET,
    FINGER,
    TRINKET,
    WEAPON,
];

const ARM = 'ARM';
const STA = 'STA';
const AGI = 'AGI';
const DEF = 'DEF';
const DGE = 'DGE';
const ATP = 'ATP';
const STR = 'STR';
const HIT = 'HIT';
const CRI = 'CRI';
const HST = 'HST';

const STATS = [
    ARM,
    STA,
    AGI,
    DEF,
    DGE,
    ATP,
    STR,
    HIT,
    CRI,
    HST,
];

const COUNT_BY_SLOT = [
    HEAD => 1,
    NECK => 1,
    SHOULDER => 1,
    BACK => 1,
    CHEST => 1,
    WRIST => 1,
    HANDS => 1,
    WAIST => 1,
    LEGS => 1,
    FEET => 1,
    FINGER => 2,
    TRINKET => 2,
    WEAPON => 1,
];

const STAT_WEIGHT_TANK = [
    ARM => 0.23,
    STA => 1.00,
    AGI => 0.92,
    DEF => 2.00,
    DGE => 16.67,
];

const STAT_WEIGHT_THREAT = [
    ATP => 0.5,
    STR => 1,
    AGI => 0.45,
    HIT => 6.69,
    CRI => 8.97,
    HST => 9.01,
];

$db = new SQLite3('db.sqlite3');

class Item {

    private $Id;
    private $Name;
    private $ARM;
    private $STA;
    private $AGI;
    private $DEF;
    private $DGE;
    private $ATP;
    private $STR;
    private $HIT;
    private $CRI;
    private $HST;
    private $SetBonus;

    function __construct($args) {
        foreach($args as $key => $val) {
            $this->$key = $val;
        }
    }

    public function getName() {
        return $this->Name;
    }

    public function getSlot() {
        return $this->Slot;
    }

    public function getStat($stat) {
        if (in_array($stat, STATS)) {
            return $this->$stat;
        } else {
            throw new Exception("Illegal stat access.");
        }
    }
}

class GearSet {

    private $HEAD;
    private $NECK;
    private $SHOULDER;
    private $BACK;
    private $CHEST;
    private $WRIST;
    private $HANDS;
    private $WAIST;
    private $LEGS;
    private $FEET;
    private $FINGER;
    private $TRINKET;
    private $WEAPON;

    private $set_stats;

    function __construct($args) {
        foreach($args as $key => $val) {
            $this->$key = $val;
        }

        $this->set_stats = array_fill_keys(STATS, 0);
        $this->generateStats();
    }

    private function generateStats() {
        foreach(SLOTS as $slot) {
            $item = $this->$slot;
            foreach(STATS as $stat) {
                $this->set_stats[$stat] += $item->getStat($stat);
            }
        }
    }

    function printSet() {
        foreach(SLOTS as $slot) {
            $label = rightPad(8, $slot);
            print("{$label} : {$this->$slot->getName()}\n");
        }
    }

    public function printStats() {
        foreach(STATS as $stat) {
            print("{$stat} : {$this->set_stats[$stat]}\n");
        }
    }
}

function getAllGear($db) {
    $result = [];
    foreach(COUNT_BY_SLOT as $slot => $count) {
        $result[$slot] = getGearForSlot($db, $slot);
    }
    return $result;
}

function getGearForSlot($db, $slot) {
    $statement = $db->prepare('SELECT * FROM Item WHERE Slot = :slot;');
    $statement->bindValue('slot', $slot);
    $result = $statement->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $arr[] = new Item($row);
    }

    return $arr;
}

function getGearCombinationsForSlot($gear) {
    $slot = $gear[0]["Slot"];
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

function rightPad($len, $string) {
    $pad = str_repeat(" ", $len-strlen($string));
    return "{$string}{$pad}";
}

$all_gear = getAllGear($db);
$all_gear_combinations = cartesian_product($all_gear)->asArray();
$a_gearset = new GearSet($all_gear_combinations[0]);
$a_gearset->printSet();
$a_gearset->printStats();

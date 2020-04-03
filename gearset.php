<?php

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
    private $FINGER1;
    private $FINGER2;
    private $TRINKET1;
    private $TRINKET2;
    private $WEAPON;

    private $set_stats;

    function __construct($items) {
        $count = count($items);
        foreach($items as $item) {
            $slot = $item->getSlot();

            if ($slot === FINGER && !isset($this->FINGER1)) {
                $this->FINGER1 = $item;
            } else if ($slot === FINGER) {
                $this->FINGER2 = $item;
            } else if ($slot === TRINKET && !isset($this->TRINKET1)) {
                $this->TRINKET1 = $item;
            } else if ($slot === TRINKET) {
                $this->TRINKET2 = $item;
            } else {
                $this->$slot = $item;
            }
        }

        $this->set_stats = $this->generateStats($this->set_stats);
    }

    public static function parseSetString($db, $set_string) {
        $items = [];
        $ids = explode(",", $set_string);

        foreach($ids as $id) {
            $items[] = $db->getItemById($id);
        };

        return new GearSet($items);
    }

    private function generateStats() {
        $stats = array_fill_keys(STATS, 0);

        foreach (SLOTS as $slot) {
            $item = $this->$slot;
            foreach(STATS as $stat) {
                $stats[$stat] += $item->getStat($stat);
            }
        }

        $stats[TNK] = $this->calculateSecondaryStats(STAT_WEIGHT_TANK, $stats);
        $stats[THR] = $this->calculateSecondaryStats(STAT_WEIGHT_THREAT, $stats);

        return $stats;
    }

    private function calculateSecondaryStats($stat_weights, $stats_arr) {
        $total_value = 0;

        foreach($stat_weights as $stat => $weight) {
            $total_value += $stats_arr[$stat] * $weight;
        }

        return $total_value;
    }

    function printSet() {
        foreach(SLOTS as $slot) {
            $label = rightPad(8, $slot);
            print("{$label} : {$this->$slot->getName()}\n");
        }
    }

    public function printStats() {
        foreach(SET_STATS as $stat) {
            print("{$stat} : {$this->set_stats[$stat]}\n");
        }
    }

    public function getSetString() {
        $ids = [];

        foreach(SLOTS as $slot) {
            $item = $this->$slot;
            $ids[] = $item->getId();
        }

        return join($ids, ",");
    }

    public function persistSet($db) {
        $db->persistSet($this);
    }

    public function getStat($stat) {
        if (array_key_exists($stat, $this->set_stats)) {
            return $this->set_stats[$stat];
        } else {
            throw new Exception("Illegal stat access: {$stat}");
        }
    }
}

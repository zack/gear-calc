<?php

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

    public function getId() {
        return $this->Id;
    }

    public function getSetBonus() {
        return $this->SetBonus;
    }

    public function getStat($stat) {
        if (in_array($stat, STATS)) {
            return $this->$stat;
        } else {
            throw new Exception("Illegal stat access.");
        }
    }
}


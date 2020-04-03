<?php

class DB {
    private $db;

    private $open_transcation;

    function __construct($filename) {
        $this->db = new SQLite3($filename);
        $this->open_transaction = false;
    }

    public function getItemsOfType($type) {
        $statement = $this->db->prepare('SELECT * FROM Item WHERE Slot = :slot;');
        $statement->bindValue('slot', $type);
        $result = $statement->execute();

        $arr = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $arr[] = new Item($row);
        }

        return $arr;
    }

    public function getItemById($id) {
        $statement = $this->db->prepare('SELECT * FROM Item WHERE Id = :id;');
        $statement->bindValue('id', $id);
        $result = $statement->execute();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return new Item($row);
        }
    }

    public function persistSet($gearSet) {
        $set_string = $gearSet->getSetString();

        $statement = "REPLACE INTO GearSet ";
        $statement .= "(SetString," . join(",", SET_STATS) . ")";
        $statement .= " VALUES ";
        $set_values = array_map(function($stat) use ($gearSet) {
            return $gearSet->getStat($stat);
        } , SET_STATS);
        $statement .= "(\"{$set_string}\"," . join(",", $set_values) . ");";
        $statement = $this->db->prepare($statement);
        $statement->execute();
    }

    private function dropSet($gearSet) {
        $set_string = $gearSet->getSetString();
        $statement = $this->db->prepare('DELETE FROM GearSet WHERE SetString = :set_string');
        $statement->bindValue('set_string', $set_string);
        $result = $statement->execute();
    }

    public function beginTransaction() {
        if($this->open_transaction === false) {
            $this->open_transaction = true;
            $this->db->exec("BEGIN;");
        } else {
            print("!! Attempted to begin a transcation while a transaction is open !!\n");
        }
    }

    public function commitTransaction() {
        if($this->open_transaction === true) {
            $this->open_transaction = false;
            $this->db->exec("COMMIT;");
        } else {
            print("!! Attempted to commit a transcation without an open transaction !!\n");
        }
    }
}

<?php

class DB {
    private $db;

    private $open_transcation;
    private $persistSetStatement;

    function __construct($filename) {
        $this->db = new SQLite3($filename);

        $this->db->exec("PRAGMA synchronous = OFF");
        $this->db->exec("PRAGMA journal_mode = MEMORY");

        $this->open_transaction = false;
        $this->persistSetStatement = $this->generatePersistSetStatement();
    }

    public function transactionIsOpen() {
        return $open_transaction;
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

    public function getSetBonuses($setId, $pieceCount) {
        $statement = $this->db->prepare('SELECT * FROM SetBonus WHERE SetId = :setId AND PieceCount <= :pieceCount;');
        $statement->bindValue('setId', $setId);
        $statement->bindValue('pieceCount', $pieceCount);
        $result = $statement->execute();

        $bonuses = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $bonuses[] = $row;
        }
        return $bonuses;
    }

    public function persistSet($gearSet) {
        foreach(SET_STATS as $STAT) {
            $this->persistSetStatement->bindValue($STAT, $gearSet->getStat($STAT));
        }

        $this->persistSetStatement->bindValue("SetString", $gearSet->getSetString());
        $this->persistSetStatement->execute();
    }

    private function generatePersistSetStatement() {
        $columns_str = join(",", SET_STATS);
        $values_arr = array_map(function($STAT) {
            return ":{$STAT}";
        }, SET_STATS);
        $values_str = join(",", $values_arr);

        $columns_str = $columns_str . ",SetString";
        $values_str = $values_str . ",:SetString";

        $statement_str = "REPLACE INTO GearSet ({$columns_str}) VALUES ({$values_str})";
        return $this->db->prepare($statement_str);
    }


    private function dropSet($gearSet) {
        $set_string = $gearSet->getSetString();
        $statement = $this->db->prepare('DELETE FROM GearSet WHERE SetString = :set_string');
        $statement->bindValue('set_string', $set_string);
        $statement->execute();
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

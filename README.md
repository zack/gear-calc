# Gear set calculator

**Still Under Construction!**

Use this to figure out which of your hundreds of thousands of possible gear
combinations is the best for any given constraints. The current plan is to
implement set bonus calculations for raw addition of stats (+2 hit, for
instance). Anything that effectively reduces to one of the base stats can
also be accomplished. There are no plans to implement more complex set bonuses
or effects (e.g. Priest T2 8-Piece). Assumes a single (2h) weapon.

## DB Setup
```
CREATE TABLE Slot (
Id INTEGER PRIMARY KEY AUTOINCREMENT,
Slot CHAR(8) PRIMARY KEY NOT NULL,
Seq INTEGER NOT NULL
);

CREATE TABLE Item (
Id INTEGER PRIMARY KEY AUTOINCREMENT,
Name VARCHAR(64) NOT NULL,
Slot CHAR(16) NOT NULL REFERENCES Slot(Slot),
ARM INTEGER NOT NULL DEFAULT 0,
STA INTEGER NOT NULL DEFAULT 0,
AGI INTEGER NOT NULL DEFAULT 0,
STR INTEGER NOT NULL DEFAULT 0,
HIT INTEGER NOT NULL DEFAULT 0,
CRI INTEGER NOT NULL DEFAULT 0,
HST INTEGER NOT NULL DEFAULT 0,
DGE INTEGER NOT NULL DEFAULT 0,
ATP INTEGER NOT NULL DEFAULT 0,
DEF INTEGER NOT NULL DEFAULT 0,
FRE INTEGER NOT NULL DEFAULT 0,
SetBonus NOT NULL DEFAULT 0 REFERENCES SetBonus(Id)
);

CREATE TABLE SetBonus (
SetId INTEGER NOT NULL,
PieceCount INTEGER NOT NULL,
ARM INTEGER NOT NULL DEFAULT 0,
STA INTEGER NOT NULL DEFAULT 0,
AGI INTEGER NOT NULL DEFAULT 0,
STR INTEGER NOT NULL DEFAULT 0,
HIT INTEGER NOT NULL DEFAULT 0,
CRI INTEGER NOT NULL DEFAULT 0,
HST INTEGER NOT NULL DEFAULT 0,
DGE INTEGER NOT NULL DEFAULT 0,
ATP INTEGER NOT NULL DEFAULT 0,
DEF INTEGER NOT NULL DEFAULT 0,
FRE INTEGER NOT NULL DEFAULT 0,
PRIMARY KEY (SetId, PieceCount)
);

INSERT INTO Slot (Slot, Seq) VALUES ('HEAD', 1);
INSERT INTO Slot (Slot, Seq) VALUES ('HEAD', 2);
INSERT INTO Slot (Slot, Seq) VALUES ('NECK', 3);
INSERT INTO Slot (Slot, Seq) VALUES ('SHOU', 4);
INSERT INTO Slot (Slot, Seq) VALUES ('BACK', 5);
INSERT INTO Slot (Slot, Seq) VALUES ('CHES', 6);
INSERT INTO Slot (Slot, Seq) VALUES ('WRIS', 7);
INSERT INTO Slot (Slot, Seq) VALUES ('HAND', 8);
INSERT INTO Slot (Slot, Seq) VALUES ('WAIS', 9);
INSERT INTO Slot (Slot, Seq) VALUES ('LEGS', 10);
INSERT INTO Slot (Slot, Seq) VALUES ('FEET', 11);
INSERT INTO Slot (Slot, Seq) VALUES ('FING', 12);
INSERT INTO Slot (Slot, Seq) VALUES ('TRIN', 13);
INSERT INTO Slot (Slot, Seq) VALUES ('WEAP', 14);

# Devilsaur Armor
INSERT INTO SetBonus (SetId, PieceCount, HIT) VALUES (1, 2, 2);
```

## Gear Dumping
Dump gear into the database use gear_dumper.php. gear.csv should look like
this:
```
Bone Ring Helm,HEAD,151,30,5,6,0,0,0,1,0,0,0
Mask of the Unforgiven,HEAD,132,12,0,0,2,1,0,1,0,0,0
Molten Helm,HEAD,150,16,0,0,0,0,0,1,0,0,49
```
etc.

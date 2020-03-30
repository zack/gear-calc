# Gear set calculator

Use this to figure out which of your hundreds of thousands of possible gear
combinations is the best for any given constraints. The current plan is to
implement set bonus calculations for raw addition of stats (+2 hit, for
instance). Anything that effectively reduces to one of the follow stats can
also be accomplished. There are no plans to implement more complex set bonuses
or effects (e.g. Priest T2 8-Piece).

## DB Setup

### Slot
CREATE TABLE Slot (
Slot Char(8) PRIMARY KEY NOT NULL,
Seq INTEGER
);

### Item
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
DGE INTEGER NOT NULL DEFAULT 0,
ATP INTEGER NOT NULL DEFAULT 0,
DEF INTEGER NOT NULL DEFAULT 0,
FRE INTEGER NOT NULL DEFAULT 0
, SetBonus NOT NULL DEFAULT 0 REFERENCES SetBonus(Id));

### SetBonus
CREATE TABLE SetBonus (
SetId INTEGER NOT NULL,
PieceCount INTEGER NOT NULL,
ARM INTEGER DEFAULT 0,
STA INTEGER DEFAULT 0,
AGI INTEGER DEFAULT 0,
STR INTEGER DEFAULT 0,
HIT INTEGER DEFAULT 0,
CRI INTEGER DEFAULT 0,
DGE INTEGER DEFAULT 0,
ATP INTEGER DEFAULT 0,
DEF INTEGER DEFAULT 0,
FRE INTEGER DEFAULT 0,
PRIMARY KEY (SetId, PieceCount)
);

## Gear Dumping
Dump gear into the database use gear_dumper.php. gear.csv should look like
this:
```
Bone Ring Helm,HEAD,151,30,5,6,0,0,1,0,0,0
Mask of the Unforgiven,HEAD,132,12,0,0,2,1,1,0,0,0
Molten Helm,HEAD,150,16,0,0,0,0,1,0,0,49
```
etc.

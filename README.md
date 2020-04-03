# Gear set calculator

**Still Under Construction!**

Use this to figure out which of your hundreds of thousands of possible gear
combinations is the best for any given constraints. The current plan is to
implement set bonus calculations for raw addition of stats (+2 hit, for
instance). Anything that effectively reduces to one of the base stats can
also be accomplished. There are no plans to implement more complex set bonuses
or effects (e.g. Priest T2 8-Piece). Assumes a single (2h) weapon.

## DB Setup
The database schema can be found in dbschema.txt. It's designed for SQLite3.

## Gear Dumping
Dump gear into the database use gear_dumper.php. gear.csv should look like
this:
```
Bone Ring Helm,HEAD,151,30,5,6,0,0,0,1,0,0,0
Mask of the Unforgiven,HEAD,132,12,0,0,2,1,0,1,0,0,0
Molten Helm,HEAD,150,16,0,0,0,0,0,1,0,0,49
```
etc.

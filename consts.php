<?php

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

const TYPES = [
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

const FINGER1 = 'FINGER1';
const FINGER2 = 'FINGER2';
const TRINKET1 = 'TRINKET1';
const TRINKET2 = 'TRINKET2';

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
    FINGER1,
    FINGER2,
    TRINKET1,
    TRINKET2,
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

const TNK = 'TNK';
const THR = 'THR';

const SET_STATS = [
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
    TNK,
    THR,
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

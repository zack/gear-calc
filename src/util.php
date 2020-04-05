<?php

function rightPad($len, $string, $spacer = " ") {
    if ($len > strlen($string)) {
        $pad = str_repeat(" ", $len - strlen($string));
        return "{$string}{$pad}";
    } else {
        return $string;
    }
}

function leftPad($len, $string, $spacer = " ") {
    if ($len > strlen($string)) {
        $pad = str_repeat($spacer, $len - strlen($string));
        return "{$pad}{$string}";
    } else {
        return $string;
    }
}

function combineArraysOfNumbersBySummation($arrays, $preserve_zeros = false) {
    $results = [];
    foreach ($arrays as $array) {
        foreach($array as $key => $value) {
            if (isset($results[$key])) {
                $results[$key] += $value;
            } else if ($value > 0 || $preserve_zeros) {
                $results[$key] = $value;
            }
        }
    }

    return $results;
}

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

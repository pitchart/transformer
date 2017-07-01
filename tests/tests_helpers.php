<?php

function plus_one() {
    return function ($number) {
        return $number + 1;
    };
}

function plus_two() {
    return function ($number) {
        return $number + 2;
    };
}

function square() {
    return function ($number) {
        return pow($number, 2);
    };
}
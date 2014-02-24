<?php

/**
 * @init ALL
 */

$array = range(1, 500);
$result = array();

/**
 * @end
 */

/**
 * @test foreach
 */

foreach ($array as $key => $value) {
    $result[] = $value * 2;
}

/**
 * @end
 */

/**
 * @test array_map
 */

$result = array_map(function($value) { return $value * 2; }, $array);

/**
 * @end
 */

<?php

$input = file_get_contents('../input.txt');

$array = str_split($input);
array_splice($array, -1);

function react(&$array, &$i) {
  $left = $array[$i];
  $right = $array[$i + 1];

  if (($left === strtoupper($right) || $left === strtolower($right)) && $left !== $right) {
    array_splice($array, $i, 2);

    decrement($i);
    return;
  }

  $i++;
}

function decrement(&$number) {
  if ($number === 0) {
    return 0;
  }

  $number--;
}

$i = 0;
while ($i < count($array) - 1) {
  react($array, $i);
}

echo count($array);

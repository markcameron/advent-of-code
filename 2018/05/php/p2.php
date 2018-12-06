<?php

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

$lengths = [];
for ($j = ord('a'); $j <= ord('z'); $j++) {
  $string = file_get_contents('../input.txt');
  $string = str_replace([chr($j), strtoupper(chr($j))], '', $string);

  $array = str_split($string);
  array_splice($array, -1);

  $i = 0;
  while ($i < count($array) - 1) {
    react($array, $i);
  }

  $lengths[chr($j)] = count($array);
}

asort($lengths);
echo current($lengths);

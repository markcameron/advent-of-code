<?php

$file = file('../list.txt');

$char_counts = array_map('count_chars', $file);
$char_counts = array_map('array_filter', $char_counts);

$twos = 0;
$threes = 0;

$checksum = function($list) use (&$twos, &$threes) {
  $twos += array_reduce($list, function ($carry, $count) {
    if ($count == 2) {
      return $carry = 1;
    }

    return $carry;
  });

  $threes += array_reduce($list, function ($carry, $count) {
    if ($count == 3) {
      return $carry = 1;
    }

    return $carry;
  });
};

$result = array_map($checksum, $char_counts);

echo $twos * $threes ."\n";

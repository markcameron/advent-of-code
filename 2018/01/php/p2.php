<?php

$file = file('../frequencies.txt');

$i = 0;
$sum = 0;
$matches = [];

$line_count = count($file);

while (TRUE) {
  $sum += (int) $file[$i%$line_count];

  if (isset($matches[$sum])) {
    echo $sum;
    break;
  }

  $matches[$sum] = $sum;

  $i++;
}

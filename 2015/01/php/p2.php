<?php

$string = file_get_contents('../input.txt');

$chars = [
  '(' => 0,
  ')' => 0,
];

for ($i = 0; $i < strlen($string); $i++) {
  $chars[$string[$i]]++;
  if ($chars['('] - $chars[')'] == -1) {
    echo($i + 1);
    break;
  }
}

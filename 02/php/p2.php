<?php

$list = file('../list.txt');

$result = similar_text($list[1], $list[2]);

$code_length = strlen($list[0]);

$string_compares = [];

foreach ($list as $compare_with) {
  foreach ($list as $code) {
    $matching_chars = similar_text($compare_with, $code);
    if ($matching_chars == ($code_length - 1)) {
      $string_compares[] = [
        $compare_with,
        $code,
      ];
    }
  }
}

foreach ($string_compares as $strings) {
  $result = array_intersect_assoc(str_split($strings[0]), str_split($strings[1]));
  echo implode('', $result);
}

//$string_compares[] = array_map(function($code) use ($compare_with) {
//  return similar_text($compare_with, $code);
//}, $list);

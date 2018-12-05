<?php

$claims = file('../input.txt');

$intersections = 0;
$fabric_map = [];

function extractClaimData($claim) {
  preg_match('/#(\d*) @ (\d*),(\d*): (\d*)x(\d*)/', $claim, $result);

  return (object) [
    'number' => $result[1],
    'left' => $result[2],
    'top' => $result[3],
    'width' => $result[4],
    'height' => $result[5],
  ];
}

function insertClaim($claim, &$fabric_map) {
  for ($column = $claim->left; $column < $claim->left + $claim->width; $column++) {
    for ($row = $claim->top; $row < $claim->top + $claim->height; $row++) {
      if (isset($fabric_map[$row][$column])) {
        $fabric_map[$row][$column]++;
        continue;
      }

      $fabric_map[$row][$column] = 0;
    }
  }
}

foreach ($claims as $row) {
  $claim = extractClaimData($row);

  insertClaim($claim, $fabric_map);
}

$intersected_area = array_reduce($fabric_map, function ($carry, $column) {
  return $carry + array_reduce($column, function($carry, $column) {
    if ($column) {
      return $carry + 1;
    }

    return $carry;
  });
});

echo $intersected_area;

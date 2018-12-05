<?php

$claims = file('../input.txt');

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
  $intersects = [];
  for ($column = $claim->left; $column < $claim->left + $claim->width; $column++) {
    for ($row = $claim->top; $row < $claim->top + $claim->height; $row++) {
      if (isset($fabric_map[$row][$column])) {
        $intersects[$fabric_map[$row][$column]] = $fabric_map[$row][$column];
      }

      $fabric_map[$row][$column] = $claim->number;
    }
  }

  if (empty($intersects)) {
    return [$claim->number];
  }

  return array_merge($intersects, [$claim->number]);
}

$intact_claims = [];
foreach ($claims as $row) {
  $claim = extractClaimData($row);

  $intersects = insertClaim($claim, $fabric_map);

  if (count($intersects) == 1) {
    $intact_claims = array_merge($intact_claims, $intersects);
  }
  else {
    $intact_claims = array_diff($intact_claims, $intersects);
  }
}

echo implode("\n", $intact_claims);

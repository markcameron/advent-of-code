<?php
$coords = file('../input.txt');

// Split into x and y coordinates
$coords = array_map(function ($coord) {
  $xy = explode(', ', $coord);
  return [
    'x' => (int) $xy[0],
    'y' => (int) $xy[1],
  ];
}, $coords);

// Calculate the min and max values for x and y to prepopulate grid
$x_vals = array_column($coords, 'x');
$y_vals = array_column($coords, 'y');

$x_min = min($x_vals) - 1;
$x_max = max($x_vals) + 1;

$y_min = min($y_vals) - 1;
$y_max = max($y_vals);

// "zero" grid with only .'s
$map = [];
for ($y = $y_min; $y <= $y_max; $y++) {
  for ($x = $x_min; $x <= $x_max; $x++) {
    $map[$x][$y] = [
      'value' => '.',
      'loop' => 0,
    ];
  }
}

foreach ($map as $x => $rows) {
  foreach ($rows as $y => $column) {
    $distance = calculateManhattanDistance($coords, $x, $y);
    if ($distance < 10000) {
      $map[$y][$x]['value'] = '#';
    }
  }
}

$map_count = calculateAreas($map);

echo $map_count['#'];

/**
 * Calculate the manhattan distance between 2 points
 *
 * @param coords
 * @param x
 * @param y
 *
 * @return
 */
function calculateManhattanDistance($coords, $x, $y) {
  $total = 0;
  foreach ($coords as $coord) {
    $total += abs($x - $coord['x']) + abs($y - $coord['y']);
  }
  return $total;
}

/**
 * Draw the map as an ascii grid
 *
 * @param map
 *
 * @return
 */
function drawMap($map) {
  foreach ($map as $y => $rows) {
    foreach ($rows as $x => $column) {
      if (ctype_upper($map[$y][$x]['value'])) {
        echo "\e[1;34;40m". $map[$y][$x]['value'] ."\e[0m";
      }
      else {
        echo $map[$y][$x]['value'];
      }
    }
    echo "\n";
  }
  echo "\n";
}

/**
 * Find the area each coordinates uses (sqaures around point that it "owns")
 *
 * @param map
 *
 * @return
 */
function calculateAreas($map) {
  $map_count = [];
  foreach ($map as $x => $rows) {
    foreach ($rows as $y => $col) {
      if (!isset($map_count[$map[$x][$y]['value']])) {
        $map_count[$map[$x][$y]['value']] = 0;
      }
      $map_count[$map[$x][$y]['value']]++;
    }
  }

  return $map_count;
}

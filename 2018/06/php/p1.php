<?php
/*
//      x-2 y+0
//      x-1 y+1
//      x-1 y-1
//      x+0 y+2
//      x+0 y-2
//      x+1 y+1
//      x+1 y-1
//      x+2 y+0
*/

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

// Insert coordinates from file into map
foreach ($coords as $index => $coord) {
  $map[$coord['y']][$coord['x']] = [
    'value' => $index,
    'loop' => -1,
  ];
}

// Loop over each co-ordinate and draw "circles" around it
$things_to_do = TRUE;
$loop = 0;

/**
 * Loop through each coordinate, drawing "circles" around each coordinate,
 * filling as far as they can till they meet other circles
 */
while ($things_to_do) {
  $loop++;
  $things_to_do = FALSE;

  foreach ($coords as $index => $coord) {

    for ($x = -$loop; $x <= $loop; $x++) {
      $y = abs($x) - $loop;
      $yone = $y;
      $ytwo = -$y;

      if ($yone === $ytwo) {
        checkConflict($map, $coord['y'] + $x, $coord['x'] + $yone, $index, $loop, $things_to_do);
        continue;
      }

      checkConflict($map, $coord['y'] + $x, $coord['x'] + $yone, $index, $loop, $things_to_do);
      checkConflict($map, $coord['y'] + $x, $coord['x'] + $ytwo, $index, $loop, $things_to_do);
    }
  }
}

// drawMap($map);

$map_count = calculateAreas($map);

asort($map_count);

$edge_values = getEdgeValues($map);

// remove edge values from map to only have finite areas
$finite_map = array_diff_key($map_count, array_flip($edge_values));

echo end($finite_map);


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
 * Check the given coordinate to see if it becomes part of the area or is already taken
 * Set value if free, set value to . if intersection of same loop.
 * Also set the loop counter to avoid using it in subsequent loops
 * when another point grows to this value
 *
 * @param map
 * @param x
 * @param y
 * @param value
 * @param loop
 * @param things_to_do
 *
 * @return
 */
function checkConflict(&$map, $x, $y, $value, $loop, &$things_to_do) {
  if (!isset($map[$x][$y]['value'])) {
    return;
  }

  // If map loop is bigger than $loop exit
  if ($map[$x][$y]['loop'] !== 0 && $map[$x][$y]['loop'] !== $loop) {
    return;
  }

  $things_to_do = TRUE;

  if ($map[$x][$y]['value'] === '.' && $map[$x][$y]['value'] < $loop) {
    $map[$x][$y] = [
      //      'value' => chr(65 + 32 + $value),
      'value' => $value,
      'loop' => $loop,
    ];
    return;
  }

  $map[$x][$y] = [
    'value' => "\e[1;32;40m". '.'."\e[0m",
    'loop' => $loop,
  ];
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

/**
 * Retrieve the values on the edge of the square
 *
 * @param map
 *
 * @return
 */
function getEdgeValues($map) {
  $edge_values = [];

  $top_row = array_column(current($map), 'value');
  $bottom_row = array_column($map[count($map)-1], 'value');
  $left_column = array_column(array_column($map, 0), 'value');
  $right_column = array_column(array_column($map, count($top_row) - 1), 'value');

  return array_unique(array_merge(
    $top_row, $bottom_row, $left_column, $right_column
  ));
}

<?php

$log = file('../input.txt');

sort($log);

$log_grid = [];

foreach ($log as $index => $row) {
  preg_match('/\[(.*) (23|00):(.*)\] (\w*) (.*)/', $row, $result);

  $date = $result[1];
  $minute = $result[3];
  $type = $result[4];
  $extra = $result[5];

  switch ($type) {
    case 'Guard':
      $guard_number = ltrim(current(explode(' ', $extra)), '#');

      if (!isset($log_grid[$guard_number][$date])) {
        $log_grid[$guard_number][$date] = array_fill(0, 60, 0);
      }
      break;
    case 'falls':
      $fell_asleep_at = $minute;
      break;
    case 'wakes':
      for ($i = $fell_asleep_at; $i < $minute; $i++) {
        $log_grid[$guard_number][$date][$i] = 1;
      }
      break;
  }
}

$minutes_asleep_by_guard = [];

foreach ($log_grid as $guard_number => $guard) {
  $minutes_asleep_by_guard[$guard_number] = array_reduce($guard, function($carry, $day) {
    return $carry + array_sum($day);
  });
}

arsort($minutes_asleep_by_guard);
$sleepiest_guard = current(array_keys($minutes_asleep_by_guard));

$sleep_grid = [];
for ($i = 0; $i < 60; $i++) {
  $sleep_grid[$i] = array_sum(array_column($log_grid[$sleepiest_guard], $i));
}

arsort($sleep_grid);
$sleepiest_minute = current(array_keys($sleep_grid));

echo $sleepiest_guard * $sleepiest_minute;

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

$sleep_grid = [];
$guards_by_minutes_slept = [];

foreach ($log_grid as $guard_number => $guard) {
  for ($i = 0; $i < 60; $i++) {
    $sleep_grid[$guard_number][$i] = array_sum(array_column($guard, $i));
  }

  arsort($sleep_grid[$guard_number]);
  $guards_by_minutes_slept[$guard_number] = current($sleep_grid[$guard_number]);
}

arsort($guards_by_minutes_slept);

$sleepiest_guard = current(array_keys($guards_by_minutes_slept));
$sleepiest_minute = current(array_keys($sleep_grid[$sleepiest_guard]));

echo $sleepiest_guard * $sleepiest_minute;

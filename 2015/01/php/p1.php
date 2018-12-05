<?php

$string = file_get_contents('../input.txt');

$char_count = count_chars($string);

echo $char_count[40] - $char_count[41];

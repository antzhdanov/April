<?php

$time = time();

$timeStartVar = 'timeStart' . $time;
$timeStopVar = 'timeStop' . $time;
$counterVar = 'counter' . $time;

@init

$$timeStartVar = microtime(true);

for ($$counterVar = 0; $$counterVar < @iterations; $$counterVar++) {
    @test
}

$$timeStopVar = microtime(true);

$timeTaken = $$timeStopVar - $$timeStartVar;

// Analysis part
$results = array(
    'name' => '@name',
    'iterations' => @iterations,
    'time_start' => $$timeStartVar,
    'time_stop' => $$timeStopVar,
    'time_summary' => $timeTaken
);

$file = fopen($_SERVER['argv'][1], 'a');
fputcsv($file, $results);
fclose($file);

<?php

ini_set('auto_detect_line_endings', TRUE);

$last_run = @file_get_contents('last_run');

if(empty($last_run)) {
	$last_run = 0;
}

$dbname = 'robocarl'

$csvfile = $argv[1];

$rows = array_map('str_getcsv', file($csvfile));
$header = array_shift($rows);

$data = [];

foreach ($rows as $row) {
	$dt = new DateTime();

	$ts = $dt->createFromFormat('m-d-Y g:i A', $row[2]);
	$date = $ts->getTimestamp();

	$date -= 3600;

	if($date <= $last_run) {
		continue;
	}

	if(!empty($row[4])) {
		$data[$date]['historic'] = $row[4];
	}

	if(!empty($row[5])) {
		$data[$date]['scan']	 = $row[5];
	}
}

ksort($data);
print_r($data);

if(count($data) == 0) {
	echo 'No data to process' . PHP_EOL;
	exit;
}

foreach($data as $time => $values) {
	echo date('m-d-Y H:i:s', $time) . PHP_EOL;

	if(isset($values['historic'])) {
		$cmd = "curl -i -XPOST 'http://localhost:8086/write?db={$dbname}' --data-binary 'glucose,type=historic value={$values['historic']} {$time}000000000'";
                echo $cmd . PHP_EOL;
                exec($cmd);
	}

	if(isset($values['scan'])) {
		$cmd = "curl -i -XPOST 'http://localhost:8086/write?db={$dbname}' --data-binary 'glucose,type=scan value={$values['scan']} {$time}000000000'";
                echo $cmd . PHP_EOL;
                exec($cmd);
	}
}

$keys = array_keys($data);
$last = end($keys);

if($last > 0) {
	file_put_contents('last_run', $last);
}

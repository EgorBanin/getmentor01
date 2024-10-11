<?php declare(strict_types=1);

$set1 = [
	[10, -100, 'foo', 1.0], // x, y, type, priority
	[11, -100, 'foo', 1.0],
	[9, -99, 'bar', 0.5],
];
$set2 = [
	[10, -99, 'foo', 0.5],
	[1, 2, 'bar', 0,5],
	[8, -101, 'bar', 1.0],
];

$result = [];
import($result, $set1);
import($result, $set2);
echo json_encode($result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

function import(array &$result, array $set) {
	foreach ($set as $point) {
		$result[] = $point;
	}
}

<?php declare(strict_types=1);

$cfg = [
	'radius' => 10,
];

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
import($result, $set1, $cfg);
import($result, $set2, $cfg);
echo json_encode($result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

function import(array &$result, array $set, array $cfg) {
	foreach ($set as $p) {
		$exists = find($result, $p, $cfg['radius']);
		if (!$exists) {
			$result[] = $p;	
		}
	}
}

function find(array $source, array $p, int $radius): ?array {
	$nearPoints = inRadius($source, $p[0], $p[1], $radius);
	usort(
		$nearPoints,
		fn($a, $b) => similarity($p, $a, $radius) <=> similarity($p, $b, $radius),
	);

	if (empty($nearPoints)) {
		return null;
	}

	return $nearPoints[0];
}

function inRadius(array $source, int $x, int $y, int $radius): array {
	$result = [];
	foreach ($source as $p) {
		if (dist($p, [$x, $y]) > $radius) {
			continue;
		}

		$result[] = $p;
	}

	return $result;
}

function dist(array $p1, array $p2): float {
	$dx = abs($p1[0] - $p2[0]);
	$dy = abs($p1[1] - $p2[0]);
       
	return sqrt(($dx**2) + ($dy**2));
}

function similarity(array $p1, array $p2, int $r): float {
	if ($p1[2] !== $p2[2]) {
		return 0.0;
	}

	$d = 1 - (dist($p1, $p2) / $r);

	return s($d);
}

function s(float $x): float {
	return 1 / (1 + pow(M_E, -$x));
}

<?php 

// Returns the next higher or lower number
function NextRelatedNumber($number, $range){
	$r = $number % $range;
	$f = $number - $r;
	$b = round($r / $range, 0);

	return ($b == 1) ? $f + $range : $f;
}

// Returns the next higher number
function NextHigherNumber($number, $range){
	$r = $number % $range;
	$f = $number - $r;
	$b = ceil($r / $range);

	return ($b == 1) ? $f + $range : $f;
}

// Returns the next lower number
function NextLowerNumber($number, $range){
	$r = $number % $range;
	$f = $number - $r;
	$b = floor($r / $range);

	return ($b == 1) ? $f + $range : $f;
}

// Returns the next related number from an array
function NextNumberArray($Number, $NumberRangeArray){

	$w = 0;
	$c = -1;
	$abstand = 0;

	$l = count($NumberRangeArray);
	for($pos=0; $pos < $l; $pos++){

		$n = $NumberRangeArray[$pos];

		$abstand = ($n < $Number) ? $Number - $n : $n - $Number;

		if ($c == -1){
			$c = $abstand;
			continue;
		}
		else if ($abstand < $c){
			$c = $abstand;
			$w = $pos;
		}
	}

	return $NumberRangeArray[$w];
}

// Examples
// --------

// 0 10 20 30 40 50 ...
print 'NextRelatedNumber: ';
print NextRelatedNumber(44, 10) . "\\n";
// returns --> 40

// 0 20 40 60 80 100 ...
print 'NextHigherNumber: ';
print NextHigherNumber(41, 20) . "\\n";
// returns --> 60

// 0 5 10 15 20 25 30 35 ...
print 'NextLowerNumber: ';
print NextLowerNumber(57, 5) . "\\n";
// returns --> 55

// Example with Array
print 'NextNumberArray: ';
print NextNumberArray(45, array(3, 8, 19, 34, 56, 89)) . "\\n";
// returns --> 34
// (45 is between 34 and 56 but 34 is the next)

?>
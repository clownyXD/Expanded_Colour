<?php

	//find best square...
	$boolCheck = true;
	$max_size_of_square = 1;
	While($boolCheck){
		$squared = $max_size_of_square*$max_size_of_square;
		$squared_inc = ($max_size_of_square+1)*($max_size_of_square+1);
		if (count($results) > $squared && count($results) <= $squared_inc){
			$boolCheck = false;
		}
		$max_size_of_square ++;
	}
	if($max_size_of_square%2 != 0){
		$max_size_of_square ++;
	}
?>
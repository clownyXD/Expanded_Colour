<?php
	//to cute per level... $results
	//turn results into 32x32...
	//$max_size_of_square

	
	// find iteration:
	$amount_of_iterations = count($amount_on_row) -1;
	$array_of_cut_Zig = array();
	//first iteration done here...
	$temparraybigger = array();
	echo var_dump($amount_on_row);
	array_push($array_of_cut_Zig,$results);
	//the actul work....
	for($t = $amount_of_iterations; $t >= 0;$t --){
		$temparraybigger = array();
		$temparray = array();
		for($f = 0; $f < $amount_on_row[$t]; $f+=2){		
			array_push($temparray, $array_of_cut_Zig[$amount_of_iterations - $t][0][$f]);
		}
		array_push($temparraybigger,$temparray);
		for($h = 2; $h < $amount_on_row[$t]; $h+=2){
			$temparray = array();
			if(($h/2)%2 == 0){
				//even row
				for($f = 0; $f < $amount_on_row[$t]; $f+=2){
					if((($f/2)%2 == 0)||($f == $amount_on_row[$t] -2)){
						//even cell or end cell
						array_push($temparray, $array_of_cut_Zig[$amount_of_iterations - $t][$h][$f]);
					}else{
						//odd cell
						array_push($temparray, $array_of_cut_Zig[$amount_of_iterations - $t][$h][$f+1]);
					}
				}
			}else{
				//odd row
				for($f = 0; $f < $amount_on_row[$t]; $f+=2){
					if((($f/2)%2 != 0)||($f == $amount_on_row[$t] -1)){
						//odd or end cell
						array_push($temparray, $array_of_cut_Zig[$amount_of_iterations - $t][$h][$f]);
					}else{
						//even cell
						array_push($temparray, $array_of_cut_Zig[$amount_of_iterations - $t][$h][$f+1]);
					}
				}
			}
			array_push($temparraybigger,$temparray);
		}
		//last row
		
		array_push($array_of_cut_Zig,$temparraybigger);
	}
	array_pop($array_of_cut_Zig);
	//echo "<script> console.log(". json_encode($array_of_cut_Zig) . ");</script>";

?>
<?php
	$start_array= array();
	
	//temp variable:
	//$size_of_file = 32;
	//variables
	if($size_of_file % 2!=0){
		$a = -1;
		$b = 1;
	}else{
		$a = 3;
		$b = -3;
	}
	$differentTarget1 = $size_of_file-2; //for selecting the right part to use
	$differentTarget2 = $size_of_file-1; //for selecting the right part to use
	//create intial table
	for($m = 0; $m < $size_of_file; $m ++){
		$temp = array();
		for($n = 0; $n < $size_of_file-2; $n ++){
			if($n ==0){
				
			}else{
				if($m %2 ==0){
					if($n %2 ==0){
						if($n<$differentTarget1){
							array_push($temp, 4);
						}elseif($n==$differentTarget1){
							array_push($temp, $a);
						}elseif($n==$differentTarget2){
							array_push($temp, $b);
						}elseif($n>$differentTarget2){
							array_push($temp, 0);
						}
					}else{
						if($n<$differentTarget1 ){
							array_push($temp, 0);
						}elseif($n==$differentTarget1){
							array_push($temp, $a);
						}elseif($n==$differentTarget2){
							array_push($temp, $b);
						}elseif($n>$differentTarget2){
							array_push($temp, -4);
						}
					}
				}else{
				if($n %2 ==0){
						if($n<$differentTarget1){
							array_push($temp, 0);
						}elseif($n==$differentTarget1){
							array_push($temp, $a);
						}elseif($n==$differentTarget2){
							array_push($temp, $b);
						}elseif($n>$differentTarget2){
							array_push($temp, -4);
						}
					}else{
						if($n<$differentTarget1){
							array_push($temp, 4);
						}elseif($n==$differentTarget1){
							array_push($temp, $a);
						}elseif($n==$differentTarget2){
							array_push($temp, $b);
						}elseif($n>$differentTarget2){
							array_push($temp, 0);
						}
					}
				}
			}
		}
		$differentTarget1 --;
		$differentTarget2 --;
		array_push($start_array, $temp);
	}
	$temp = json_encode($start_array);
	//add the values onto previous.
	$start = 5;
	
	$middle_array_complete = array();
	for($p = 0; $p < count($start_array); $p++){
		$middle_array = array();
		for($q = 0; $q < count($start_array[$p])+1; $q++){
			if($p == 0 && $q ==0){
				$start = 5;
				array_push($middle_array,$start);
			}elseif($q ==0){
				$start += $start_array[$p-1][$q];
				array_push($middle_array,$start);
			}else{
				$index = count($middle_array)-1;
				$toPush = $middle_array[$index] + $start_array[$p][$q-1];
				array_push($middle_array, $toPush);
			}
		}
		array_push($middle_array_complete,$middle_array);
	}
//column 1 and 2 work perfectly for this instance
	//rest of column 1 (column 1 starts at 1)
		$column1 = array();
		
		for ($p = 0; $p < count($start_array); $p++){
			if($p ==0 ){
				array_push($column1,0); //change 1 to 0.
				$start =0;
			}elseif($p == count($start_array) -1){
				array_push($column1,2);
			}elseif($p%2 == 0){
				$start +=4;
				array_push($column1,$start);
			}else{
				array_push($column1,3);
			}
		}
	
	//column 2
		$column2 = array();
		
		for ($p = 0; $p < count($start_array); $p++){
			if($p == 0 ){
				array_push($column2,1);
				$start =1;
			}elseif($p == count($start_array) -1){
				array_push($column2,1);
			}elseif($p%2 == 0){
				$start +=4;
				array_push($column2,$start);
			}else{
				array_push($column2,2);
			}
		}
	
	//only works on EVEN square : (axa) where a is even.
	$final_array_complete = array();
	for($p = 0; $p < count($middle_array_complete); $p++){
		$final_array = array();
		for($q = 0; $q < count($middle_array_complete[$p])+2; $q++){
			if($p == 0 && $q ==0){
				array_push($final_array,$column1[$p]);
			}elseif($q ==0){//column1
				array_push($final_array,$final_array_complete[$p-1][2]-$column1[$p]);
			}elseif($q ==1){//column 2
				array_push($final_array,$final_array[0]+$column2[$p]);
			}else{//everything else
				$index = count($final_array)-2;
				$toPush = $final_array[$index] + $middle_array_complete[$p][$q-2];
				array_push($final_array, $toPush);
			}
			
		}
		array_push($final_array_complete,$final_array);
	}	
	//places results into correct place.
	$result_array_complete = array();
	for($p = 0; $p < count($final_array_complete); $p++){
		$result_array = array();
		for($q = 0; $q < count($final_array_complete[$p]); $q++){
			if($final_array_complete[$p][$q] >= count($results)){//if image does not exist
				array_push($result_array, array("name"=> "N/A", "extension"=>"N/A")); 
			}else{
				array_push ($result_array, $results[$final_array_complete[$p][$q]]);
			}
		}
		array_push($result_array_complete, $result_array);
	}
	unset($results);
	$results = $result_array_complete;
	
	
?>
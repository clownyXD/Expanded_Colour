<?php
	$array_of_images=array();
	$reference_array = array();
	$amount_to_skip = $max_size_of_square/$amount_on_row[$i];
	$amount_cut_images = ceil((($amount_on_row[$i]*($size_per_image[$i]+$padding))+$padding)/$default_size);
	for($j=0; $j < $max_size_of_square; $j+=$amount_to_skip){
		$temp_array = array();
		for($k=0; $k < $max_size_of_square; $k+=$amount_to_skip){
			$location = $k+($j*$max_size_of_square);
			$result_name = $results[$location]['name'];
			$result_format = $results[$location]['format'];
			$file_name_and_format = $result_name.".".$result_format;
			$x = $k/$amount_to_skip;
			$y = $j/$amount_to_skip;
			$array_of_images[] = new Images($location,$file_name_and_format,$x,$y,$size_per_image[$i],$padding);
			$temp_array[]=count($array_of_images);
		}
		$reference_array[]=$temp_array;// stores a reference of where each image is stored
	}
?>
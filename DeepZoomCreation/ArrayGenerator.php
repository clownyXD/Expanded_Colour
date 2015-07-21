<?php
	include "../XML-Handlers/LoadXML.php";	//loads data
	$results = $images;		//keeps to old style (use to be DB)
	if(count($results)!=0){
		if ($layout == "Hilberts"){	//hilbert needs to be of sizes 8x8,16x16,32x32, etc...
			include "SizeOfSquare-Hilberts.php";
		}else{
			include "SizeOfSquare.php";
		}
		$default_size = 256;
		$padding = 5;
		$factors = array();
		$canBeDivided = true;
		$size = $max_size_of_square;
		array_push($factors, $size);
		while($canBeDivided){//finds lowest value the square can be (over 8)
			if($size % 2 == 0 && $size/ 2 >5){
				$size /= 2;
				array_push($factors, $size);
			}else{
				$canBeDivided = false;
			}
		}
		
		$amount_on_row = array_reverse($factors);
		$size_per_image = array(10,25,50,100,150,200);
	}
?>
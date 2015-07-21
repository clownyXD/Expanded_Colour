<?php 
	include "../XML-Handlers/LoadWidth.php";
	include "../DeepZoomCreation/ArrayGenerator.php";
	$width_array = array();
	for($i=0; $i <= 16; $i++){
		$temp_width = ($size_per_image[$i]+$padding)*$amount_on_row[$i] + $padding;
		array_push($width_array,$temp_width);
	}
	echo json_encode($width_array);

?>
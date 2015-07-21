<?php
	//fill in shapes.

	if($dest_x-($padding) < $padding && $temp_x > 0 && $dest_y-($padding) < $padding && $temp_y > 0){#
		if($array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getGrey() == -1){
			$colours = hue($array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getHue(),1,0.5);
		}else{
			$colours = hue(0,0,$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getGrey()/100);
			//echo var_dump($colours);
		}
		$colour = imagecolorallocate($output,$colours[0],$colours[1],$colours[2]);	
		imagefilledrectangle($output,0,0,($dest_x-($padding)),($dest_y-($padding)), $colour);
	}
	if($dest_x-($padding) < $padding && $temp_x > 0){
		if($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getGrey() == -1){
			$colours = hue($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getHue(),1,0.5);
		}else{
			$colours = hue(0,0,$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getGrey()/100);
		}
		$colour = imagecolorallocate($output,$colours[0],$colours[1],$colours[2]);	
		imagefilledrectangle($output,0,($dest_y-($padding)),($dest_x-($padding)),($dest_y+$source_h+($padding)), $colour);
	}
	if($dest_y-($padding) < $padding && $temp_y > 0){
		if($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getGrey() == -1){
			$colours = hue($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getHue(),1,0.5);
		}else{
			$colours = hue(0,0,$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getGrey()/100);
		}
		$colour = imagecolorallocate($output,$colours[0],$colours[1],$colours[2]);	
		imagefilledrectangle($output,($dest_x-($padding)),0,($dest_x+$source_w+($padding)),($dest_y-($padding)), $colour);
	}
	//echo "<br><br>-------------------------------COLOURS-----------------------------------<br>";
	/* echo "<script> console.log($temp_x)</script>";
	echo "<script> console.log($temp_y)</script>";
	echo "<script> console.log(".json_encode(hue($array_of_images[$reference_array[$temp_y][$temp_x]]->getHue())).")</script>"; */
	//echo json_encode(hue($array_of_images[$reference_array[$temp_y][$temp_x]]->getHue(),1,0.5));
	if($array_of_images[$reference_array[$temp_y][$temp_x]]->getGrey() == -1){
			$colours = hue($array_of_images[$reference_array[$temp_y][$temp_x]]->getHue(),1,0.5);
		}else{
			$colours = hue(0,0,$array_of_images[$reference_array[$temp_y][$temp_x]]->getGrey()/100);
		}
	$colour = imagecolorallocate($output,$colours[0],$colours[1],$colours[2]);	
	//echo "<br>Hue: ".$array_of_images[$reference_array[$temp_y][$temp_x]]->getHue() . "<br> ----------------------------------------------------------- <br><br>";
	imagefilledrectangle($output, ($dest_x-($padding)), ($dest_y-($padding)), ($dest_x+$source_w+($padding)), ($dest_y+$source_h+($padding)), $colour);
?>
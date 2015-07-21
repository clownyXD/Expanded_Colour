<?php
	//checks current selected image, and the 3 neighbouring images top/left/top  left to make border.
	if($i < (round(count($amount_on_row)*(1/3)))){
		//years
		if($dest_x-($padding) < $padding && $temp_x > 0 && $dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getYear() >= 0){ //when images don't exit, do nothing.
				$r = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getYear()][0];
				$g = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getYear()][1];
				$bl = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getYear()][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,0,($dest_x-($padding)),($dest_y-($padding)), $colour);
			}
		}
		if($dest_x-($padding) < $padding && $temp_x > 0){
			if($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getYear() >= 0){
				$r = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getYear()][0];
				$g = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getYear()][1];
				$bl = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getYear()][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,($dest_y-($padding)),($dest_x-($padding)),($dest_y+$source_h+($padding)), $colour);
			}
		}
		if($dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getYear() >= 0){
				$r = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getYear()][0];
				$g = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getYear()][1];
				$bl = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getYear()][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,($dest_x-($padding)),0,($dest_x+$source_w+($padding)),($dest_y-($padding)), $colour);
			}
		}if($array_of_images[$reference_array[$temp_y][$temp_x]]->getYear() >= 0){
			$r = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x]]->getYear()][0];
			$g = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x]]->getYear()][1];
			$bl = $Yeararrayofcolours[date("Y")-$array_of_images[$reference_array[$temp_y][$temp_x]]->getYear()][2];
			$colour = imagecolorallocate($output,$r,$g,$bl);		
			imagefilledrectangle($output, ($dest_x-($padding)), ($dest_y-($padding)), ($dest_x+$source_w+($padding)), ($dest_y+$source_h+($padding)), $colour);
		}
	}elseif($i >= (round(count($amount_on_row)*(1/3))) && $i < (round(count($amount_on_row)*(2/3)))){
		//months
		if($dest_x-($padding) < $padding && $temp_x > 0 && $dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getMonth()-1 >= 0){
				$r = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getMonth()-1][0];
				$g = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getMonth()-1][1];
				$bl = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getMonth()-1][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,0,($dest_x-($padding)),($dest_y-($padding)), $colour);
			}
		}
		if($dest_x-($padding) < $padding && $temp_x > 0){
			if($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getMonth()-1 >= 0){
				$r = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getMonth()-1][0];
				$g = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getMonth()-1][1];
				$bl = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x-1]]->getMonth()-1][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,($dest_y-($padding)),($dest_x-($padding)),($dest_y+$source_h+($padding)), $colour);
			}
		}
		if($dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getMonth()-1 >= 0){
			$r = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getMonth()-1][0];
			$g = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getMonth()-1][1];
			$bl = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y-1][$temp_x]]->getMonth()-1][2];
			$colour = imagecolorallocate($output,$r,$g,$bl);	
			imagefilledrectangle($output,($dest_x-($padding)),0,($dest_x+$source_w+($padding)),($dest_y-($padding)), $colour);
			}
		}
		if($array_of_images[$reference_array[$temp_y][$temp_x]]->getMonth()-1 >= 0){
			$r = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x]]->getMonth()-1][0];
			$g = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x]]->getMonth()-1][1];
			$bl = $Montharrayofcolours[$array_of_images[$reference_array[$temp_y][$temp_x]]->getMonth()-1][2];
			$colour = imagecolorallocate($output,$r,$g,$bl);		
			imagefilledrectangle($output, ($dest_x-($padding)), ($dest_y-($padding)), ($dest_x+$source_w+($padding)), ($dest_y+$source_h+($padding)), $colour);
		}
		
	}else{
		//days
		if($dest_x-($padding) < $padding && $temp_x > 0 && $dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x-1]]->getDay() >= 0){
				$r = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][0];
				$g = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][1];
				$bl = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,0,($dest_x-($padding)),($dest_y-($padding)), $colour);
			}
		}
		if($dest_x-($padding) < $padding && $temp_x > 0){
			if($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getDay() >= 0){
				$r = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getDay())][0];
				$g = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getDay())][1];
				$bl = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x-1]]->getDay())][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,0,($dest_y-($padding)),($dest_x-($padding)),($dest_y+$source_h+($padding)), $colour);
			}
		}
		if($dest_y-($padding) < $padding && $temp_y > 0){
			if($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getDay() >= 0){
				$r = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getDay())][0];
				$g = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getDay())][1];
				$bl = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y-1][$temp_x]]->getDay())][2];
				$colour = imagecolorallocate($output,$r,$g,$bl);	
				imagefilledrectangle($output,($dest_x-($padding)),0,($dest_x+$source_w+($padding)),($dest_y-($padding)), $colour);
			}
		}
		if($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay() >= 0){
			$week = round((intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())/7));
			$r = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][0];
			$g = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][1];
			$bl = $Daysarrayofcolours[intval($array_of_images[$reference_array[$temp_y][$temp_x]]->getDay())][2];
			$colour = imagecolorallocate($output,$r,$g,$bl);		
			imagefilledrectangle($output, ($dest_x-($padding)), ($dest_y-($padding)), ($dest_x+$source_w+($padding)), ($dest_y+$source_h+($padding)), $colour);
		}
	}
		
?>
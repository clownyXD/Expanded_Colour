<?php	
	$As = $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_start();
	$Bs = $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_end();
	$Cs = $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_start();
	$Ds = $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end();
	$Es = $current_x_position_s;
	$Fs = $current_x_position_f;
	$Gs = $current_y_position_s;
	$Hs = $current_y_position_f;
	//the little s refers to shape - only for naming.
	$A =FALSE;
	$B =FALSE;
	$C	=FALSE;
	$D =FALSE;
	//these are defined to say whether a side is inside the crop...
	//					Gs
	//			-----------------
	//			|		Cs		|
	//			|	---------	|
	//	  	  Es| As|		|Bs	|Fs
	//			|	| image	|	|
	//			|	---------	|
	//			|		Ds		|<----Cropping space
	//			-----------------
	//					Hs
	$end_width = ($width == $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_end() + $padding);
	$end_height = ($height == $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end() + $padding);
	$default_width = (($scale+$padding) <= $default_size)&&(($scale+($padding)*2) >= $default_size);
	$default_height = (($scale+$padding) <= $default_size)&&(($scale+($padding)*2) >= $default_size);
	$default_width_temp = ($array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_end()+$scale)-($x*$default_size);
	$default_height_temp = ($array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end()+$scale)-($y*$default_size);
	$default_width = (($default_width_temp <= $default_size)&&($default_width_temp+$padding >= $default_size));
	$default_height = (($default_height <= $default_size)&&($default_height+$padding >= $default_size));
	$size_boolean = $scale+($padding*2) > $default_size;
	
	if($As >= $Es && $As <= $Fs){
		//A is inside Vertically
		$A = TRUE;
	}
	if($Bs >= $Es && $Bs <= $Fs){
		//B is inside Vertically
		$B = TRUE;
	}
	if($Cs >= $Gs && $Cs <= $Hs){
		//C is inside Horizontally
		$C = TRUE;
	}
	if($Ds >= $Gs && $Ds <= $Hs){
		//D is inside Horizontally
		$D = TRUE;
	}
?>
<?php
	//make arrays for amount of images to use
	$total = 0;
	$notDone = TRUE; // just for Zigzag
	include "ArrayGenerator.php";
	
	if($type == "Time"){
		$smallest_year = intval($results[0]['year']);//find the year!	-for colouring
		include "../XML-Handlers/EarlyYear.php";
		include "ColourCreation/ColourDeclaration.php";
		include "ArrowCreation/ArrowLayouts.php";//stores the co-orinates of each arrow.
	}else{
		include_once "ContentBasedBrowsing/Hue.php";
	}
	if ($layout == "ZigZag"){
		$DC = TRUE;
		$size_of_file=$max_size_of_square;
		include "Layout/ZigZag.php";
		include "Layout/ZigZagSelection.php";
	}elseif($layout == "Hilberts"){
		include_once "Layout/HilbertCurve.php";
		$test = new hilbert();
		$test-> set_amount($max_size_of_square);
		$test_array = $test->test_pth();
		$temp_array = array();
		for($i = 0; $i < count($test_array); $i ++){
			for ($j = 0; $j <count($test_array[$i]); $j++){
				if ($test_array[$i][$j] > count($results)){
					array_push($temp_array, array("name"=> "N/A", "extension"=>"N/A"));
				}else{
					array_push($temp_array, $results[$test_array[$i][$j]-1]);
				}
			}
		}
		unset($results);
		$results = $temp_array;
	}
	$allWidths = array();
	//create images
	include_once "ImageDataStorageClass.php";
	$counter = 1;
	for ($currentZoom = 0; $currentZoom < count($size_per_image); $currentZoom++){
		$widtharray = array();
		for ($i=0; $i <count($amount_on_row); $i++){
			mkdir("../ResearchUploads/$user/Stitched/$type/$layout/".$size_per_image[$currentZoom]."/$i", 0777); // forms new directories.
			$scale = $size_per_image[$currentZoom];
			$width = ($amount_on_row[$i] * ($scale+($padding*2)))+$padding;
			$height = ($amount_on_row[$i] * ($scale+($padding*2)))+$padding;
			array_push($widtharray, ($amount_on_row[$i] * ($scale+($padding*2))));			
			{//generating the objects of images
				$array_of_images=array();
				$reference_array = array();
				$amount_to_skip = $max_size_of_square/$amount_on_row[$i];
				$amount_cut_images = ceil((($amount_on_row[$i]*($scale+($padding*2)))+$padding)/$default_size);
				if($layout != "ZigZag"){//to find best image for zigzag - need to figure out!
					for($j=0; $j < $max_size_of_square; $j+=$amount_to_skip){
					$temp_array = array();
						
						for($k=0; $k < $max_size_of_square; $k+=$amount_to_skip){
							//this stores the correct images per layer.
							$location = $k+($j*$max_size_of_square);
							if(count($results) > $location){
								$result_name = $results[$location]['name'];
								$result_format = $results[$location]['extension'];
								if($result_name != "N/A"){
									$result_number = $results[$location]['number'];
									$result_y = $results[$location]['year'];
									$result_m = $results[$location]['month'];
									$result_d = $results[$location]['day'];
									$result_h = $results[$location]['hueMode'];
									$result_g = $results[$location]['greyMode'];
								}else{
									$result_number = -1;
									$result_y = -1;
									$result_m = -1;
									$result_d = -1;
									$result_h = -2;
									$result_g = -2;
								}
							}else{
								$result_name = "N/A";
								$result_format = "N/A";
								$result_number = -1;
								$result_y = -1;
								$result_m = -1;
								$result_d = -1;
								$result_h = -2;
								$result_g = -2;
							}
							$file_name_and_format = $result_name.".".$result_format;
							$x = $k/$amount_to_skip;
							$y = $j/$amount_to_skip;
													
							$array_of_images[] = new Images();
							$array_of_images[count($array_of_images)-1]->construct($location,$file_name_and_format,$x,$y,$scale,$padding,$result_number,$result_y,$result_m,$result_d,$result_h,$result_g);
							$temp_array[]=count($array_of_images)-1;

						}
						
						$reference_array[]=$temp_array;// stores a reference of where each image is stored
					}
				}else{
					$pos = count($array_of_cut_Zig)-1-$i;
					unset($results);
					$results = $array_of_cut_Zig[$pos];
					for($j=0; $j < count($results); $j++){
					$temp_array = array();
						for($k=0; $k < count($results); $k++){
							//this stores the correct images per layer.
							if(isset($results[$j][$k])){
								$result_name = $results[$j][$k]['name'];
								$result_format = $results[$j][$k]['extension'];
								if($result_name != "N/A"){
									$result_number = $results[$j][$k]['number'];
									$result_y = $results[$j][$k]['year'];
									$result_m = $results[$j][$k]['month'];
									$result_d = $results[$j][$k]['day'];
									$result_h = $results[$j][$k]['hueMode'];
									$result_g = $results[$j][$k]['greyMode'];
								}else{
									$result_number = -1;
									$result_y = -1;
									$result_m = -1;
									$result_d = -1;
									$result_h = -2;
									$result_g = -2;
								}
							}else{
								$result_name = "N/A";
								$result_format = "N/A";
								$result_number = -1;
								$result_y = -1;
								$result_m = -1;
								$result_d = -1;
								$result_h = -2;
								$result_g = -2;
							}
							$file_name_and_format = $result_name.".".$result_format;
							$x = $k;
							$y = $j;
							$array_of_images[] = new Images();
							$location = ($j * count($results)) + $k;
							$array_of_images[count($array_of_images)-1]->construct($location,$file_name_and_format,$x,$y,$scale,$padding,$result_number,$result_y,$result_m,$result_d,$result_h,$result_g);
							$temp_array[]=count($array_of_images)-1;
						}
						
						$reference_array[]=$temp_array;// stores a reference of where each image is stored
					}
				}
			}			
			$count_ref_x = 0;
			$count_ref_y = 0;//to use reference array to know which image we are looking at
			
			for($y=0; $y<$amount_cut_images; $y++){
				$count_ref_x = 0;
				$temp_ref_y = $count_ref_y;
				for($x=0; $x<$amount_cut_images; $x++){
					$temp_ref_x = $count_ref_x;
					$count_ref_y = $temp_ref_y;
					{//declaring cropping sizes
						$current_x_position_s = $default_size*$x;
						$current_y_position_s = $default_size*$y;
						$current_x_position_f = $default_size*($x+1);
						$current_y_position_f = $default_size*($y+1);;//these values are purely to reference the cutting section.
						if($x+1==$amount_cut_images){
							$current_x_position_f = $width;
						}
						if ($y+1==$amount_cut_images){
							$current_y_position_f = $height;
						}
					}
					//create image
					$current_height = $default_size;
					$current_width = $default_size;
					$currentFullHeight = $default_size*($y+1);
					$currentFullWidth = $default_size*($x+1);
					if($x+1==$amount_cut_images){
						if(($width-$default_size*$x) - $padding > 0){
							$current_width = ($width-$default_size*$x) - $padding;
						}else{
							$current_width = 0;
						}
						
						$currentFullWidth = $width - $padding;
					}
					if($y+1==$amount_cut_images){
						if(($height-$default_size*$y) - $padding > 0){
							$current_height = ($height-$default_size*$y - $padding);
						}else{
							$current_height = 0;
						}
						$currentFullHeight = $height - $padding;
					}
					if($current_height != 0 && $current_width != 0){
						$output = imagecreatetruecolor ($current_width,$current_height);//creates image
						$colour = imagecolorallocate($output, 76, 76, 76);		//gives grey background
						imagefill($output, 0, 0, $colour);
						
						if(!($current_width<= $padding || $current_height<= $padding)){
							
							$while_boolean = TRUE;
							while($while_boolean){
								$Add_Image = FALSE;
								$temp_x = $count_ref_x;
								$temp_y = $count_ref_y;
									
								if(isset($reference_array[$count_ref_y][$count_ref_x])){
									if( isset($array_of_images[$reference_array[$count_ref_y][$count_ref_x]])){
										include "DeepZoomDeclareVariables.php";
										include "DeepZoomRules.php";
										include "DeepZoomAddImage.php";		
									}else{
										$while_boolean = FALSE;
									}
								}else{
									$while_boolean = FALSE;
								}
							}
						}
						imagejpeg($output, "../ResearchUploads/$user/Stitched/$type/$layout/".$size_per_image[$currentZoom]."/$i/".$x."_".$y.".jpg");	
						$total ++;
					}
				}
			}
		}
		array_push($allWidths, $widtharray);
		
	}
	include "../XML-Handlers/WidthXML.php";
?>
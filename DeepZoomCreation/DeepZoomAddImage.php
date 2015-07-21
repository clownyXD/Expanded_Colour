<?php

	if ($Add_Image){
		{//add image
			if( is_file("../ResearchUploads/$user/Thumb-Resized-$scale/".$array_of_images[$reference_array[$temp_y][$temp_x]]->getPath())){
				$input = imagecreatefromjpeg("../ResearchUploads/$user/Thumb-Resized-$scale/".$array_of_images[$reference_array[$temp_y][$temp_x]]->getPath()); //creates image
				$source_scale = $array_of_images[$reference_array[$temp_y][$temp_x]]->getSizeImage();
				{//Get Dest_x
					$cut_x = 0;
					$dest_x = $As-$Es;//re make this and it should work....
					if($dest_x < 0){
						$cut_x = $dest_x;
						$dest_x = 0;
					}
					
				}
				{//Get Dest_y
					$cut_y = 0;
					$dest_y = $Cs-$Gs;
					if($dest_y < 0){
						$cut_y = $dest_y;
						$dest_y = 0;		
					}
				}

				{//Get source points
					$source_x = 0 - $cut_x;
					$source_y = 0 - $cut_y;
				}
				{//Get source widths
					$source_w = $source_scale - $source_x;
					$source_h = $source_scale - $source_y;
				}
				if(!($type == "Colour"||$type == "ColourMed")){
					include "ColourCreation/ColoursProcess.php";//colour handler
				}else{
					include "ContentBasedBrowsing/ColourProcess.php";
					//makes background the Hue
				}
				$temp_width = $temp_x*($scale+($padding*2))+10+($padding*2);
				$temp_height = $temp_y*($scale+($padding*2))+10+($padding*2)*2;
				$yellow = imagecolorallocate($output, 200, 200, 200);
				$font = 'Font/ArialBold.ttf'; //if font was needed.
				imagecopy($output,$input,$dest_x,$dest_y,$source_x,$source_y,$source_w,$source_h); /// creates images
				for($a=$temp_y-1; $a <= $temp_y+1; $a++){
					for($b=$temp_x-1; $b <= $temp_x+1; $b++){
						if($a >= 0 && $b >=0 && $a < count($reference_array) && $b < count($reference_array)){
							if(!($type == "Colour"||$type == "ColourMed")){include "ArrowCreation/ArrowProcess.php";}	//only run arrows on TBB
							$temp_width = ($b)*($scale+($padding*2))+5+($padding) - ($default_size*$x);
							$temp_height = ($a)*($scale+($padding*2))+5+($padding)*3 - ($default_size*$y);													
						}
					}
				}
				$XMLname = $array_of_images[$reference_array[$temp_y][$temp_x]]->getPath();
				$XMLXstart = $array_of_images[$reference_array[$temp_y][$temp_x]]->get_x_start();
				$XMLXend = $array_of_images[$reference_array[$temp_y][$temp_x]]->get_x_end();
				$XMLYstart = $array_of_images[$reference_array[$temp_y][$temp_x]]->get_y_start();
				$XMLYend = $array_of_images[$reference_array[$temp_y][$temp_x]]->get_y_end();
				if($array_of_images[$reference_array[$temp_y][$temp_x]]->getFullyStored()){
					include "../XML-Handlers/LayerXML.php";//stores co-ordinates in XML
				}
			}
		}
	}

?>
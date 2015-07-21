<?php
	set_time_limit(0);
	if (!isset($_POST["user"])){
		$user = 1;
	}else{
		$user = $_POST["user"];
	}
	echo $user."<br>";
	include("ak_php_img_lib_1.0.php");
	$extensions = array("jpg","jpeg");
	if (!is_dir("../ResearchUploads")){	
		mkdir("../ResearchUploads", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user")){	
		mkdir("../ResearchUploads/$user", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Source")){	
		mkdir("../ResearchUploads/$user/Source", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Resized")){	
		mkdir("../ResearchUploads/$user/Resized", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Stitched")){	
		mkdir("../ResearchUploads/$user/Stitched", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Thumb-Resized-25")){	
			mkdir("../ResearchUploads/$user/Thumb-Resized-25", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Thumb-Resized-10")){	
		mkdir("../ResearchUploads/$user/Thumb-Resized-10", 0777); // forms new directories.
	}
	for($j =50; $j<=200; $j+=50){
		if (!is_dir("../ResearchUploads/$user/Thumb-Resized-$j")){	
			mkdir("../ResearchUploads/$user/Thumb-Resized-$j", 0777); // forms new directories.
		}
	}
	echo "<script>console.log(".json_encode($_FILES).")</script>";
	if(isset($_FILES['files'])){
		$file_name_array = $_FILES['files']['name']; // The file name
		$file_tmp_loc_array = $_FILES['files']['tmp_name']; // File in the PHP tmp folder
		$file_type_array = $_FILES['files']['type']; // The type of file it is
		$file_size_array = $_FILES['files']['size']; // File size in bytes
		$file_error_msg_array = $_FILES['files']['error']; // 0 for false... and 1 for true
		$max_file_size =0;
		//$sql = "INSERT INTO Image_Table(Name,Date,Format,Size) VALUES";
		for($i = 0; $i < count($file_tmp_loc_array); $i++){
			echo round(($i/count($file_tmp_loc_array))*100)."% Complete <br>";
			ob_flush();
			//echo "I: $i of: ".count($file_tmp_loc_array) . "<br>";
			$file_name = $file_name_array[$i];
		
			include "Duplicate.php";
			$file_tmp_loc = $file_tmp_loc_array[$i];
			$file_type = $file_type_array[$i];
			$file_size = $file_size_array[$i];
			$file_error_msg = $file_error_msg_array[$i];
			$file_name_explode = explode('.',$file_name);
			$file_ext=end($file_name_explode);
			$file_name ="";
			for($j = 0; $j < sizeof($file_name_explode) -1; $j++){
				$file_name .= $file_name_explode[$j] . ".";
			}
			$file_name = substr($file_name, 0, strlen($file_name)-1);
			$max_file_size+=$file_size;
			$j = $i +1;
			if (!$file_tmp_loc) { // if file not chosen
				//echo "ERROR: Please browse for a file before clicking the upload button.";
			}else{	
				if(!(in_array(strtolower($file_ext),$extensions ))){
					//echo "<br>ERROR: extension not allowed: |$file_name.$file_ext|";
					
				}else{
					if ($file_error_msg > 0) { // if file upload error f is equal to 1
						//echo "<br>Return Code: ".$file_error_msg;
					}else{
						$moveResult = move_uploaded_file($file_tmp_loc, "../ResearchUploads/$user/Source/$file_name.$file_ext");
						if ($moveResult != true) {
							//echo "<br>ERROR: File not uploaded. Try again.";
						}else{
							//echo "stored";
							
							$target_file = "../ResearchUploads/$user/Source/$file_name.$file_ext";
							$resized_file = "../ResearchUploads/$user/Resized/$file_name.$file_ext";
							$wmax = 300;$hmax = 300;
							ak_img_resize($target_file, $resized_file, $wmax, $hmax, strtolower($file_ext));
							
							$target_file = "../ResearchUploads/$user/Resized/$file_name.$file_ext";
							
							include "colour.php";
							
							//echo $modeNumber."<br>";
							//echo $mode."<br>";
							$thumbnail = "../ResearchUploads/$user/Thumb-Resized-200/$file_name.$file_ext";
							$wthumb = 200;$hthumb = 200;
							ak_img_thumb($target_file, $thumbnail, $wthumb, $hthumb, strtolower($file_ext));
							$target_file = "../ResearchUploads/$user/Thumb-Resized-200/$file_name.$file_ext";
							for($j =50; $j<200; $j+=50){
								$resized_file = "../ResearchUploads/$user/Thumb-Resized-$j/$file_name.$file_ext";
								ak_img_resize($target_file, $resized_file, $j, $j, strtolower($file_ext));
							}
							$resized_file = "../ResearchUploads/$user/Thumb-Resized-25/$file_name.$file_ext";
							ak_img_resize($target_file, $resized_file, 25, 25, strtolower($file_ext));
							$resized_file = "../ResearchUploads/$user/Thumb-Resized-10/$file_name.$file_ext";
							ak_img_resize($target_file, $resized_file, 10, 10, strtolower($file_ext));
							
							//get creation date
							$exif_data = exif_read_data ("../ResearchUploads/$user/Source/$file_name.$file_ext");
							//echo $exif_data['DateTimeOriginal']."<br>";
							if (!empty($exif_data['DateTimeOriginal'])) {
								$date_taken = $exif_data['DateTimeOriginal'];
							}else{
								$date_taken =date("Y:m:d H:i:s");
							}
							include "../XML-Handlers/ManageXML.php";
						}
					}
				}
			}			
		}
	}else{
		echo "No files uploaded - due to an unknown error -.-";
	}
	$file = "../ResearchUploads/$user/file_data.xml";
	include "../XML-Handlers/StitchStatusReset-XML.php";
	//echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://co-project.lboro.ac.uk/cocbl/Research?type='.$type.'">';
?>
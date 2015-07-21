<?php
	set_time_limit(0);
	
	if (isset($_GET["layout"])) {$layout = $_GET["layout"];} else {$layout = "Linear";}
	if (isset($_GET["type"])) {$type = $_GET["type"];}else{$type = "Time";}
	if (isset($_GET["user"])) {$user = ($_GET["user"]);}else{$user=1;}
	if (is_dir("../ResearchUploads/$user/Source")){	
		if($type=="All"){
			include "../XML-Handlers/UploadStatus_load-XML.php";
			if($vals[0][0] == 0){//Time Hilberts
				$type = "Time";$layout = "Hilberts";
				runCompile($type,$layout,$user);
			}
			if($vals[0][1] == 0){//Time Linear
				$type = "Time";$layout = "Linear";
				runCompile($type,$layout,$user);
			}
			if($vals[0][2] == 0){//Time ZigZag
				$type = "Time";$layout = "ZigZag";
				runCompile($type,$layout,$user);
			}
			if($vals[1][0] == 0){//Colour Hilberts
				$type = "Colour";$layout = "Hilberts";
				runCompile($type,$layout,$user);
			}
			if($vals[1][1] == 0){//Colour Linear
				$type = "Colour";$layout = "Linear";
				runCompile($type,$layout,$user);
			}
			if($vals[1][2] == 0){//Colour ZigZag
				$type = "Colour";$layout = "ZigZag";
				runCompile($type,$layout,$user);
			}
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://localhost/Research?user='.$user.'">';//takes user to their images
		}else{
			runCompile($type,$layout,$user);
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://localhost/Research?user='.$user.'&type='.$type.'&layout='.$layout.'">';
		}
	}else{
		echo "No Images uploaded";
	}
	
	function runCompile($type,$layout,$user){
		include "../XML-Handlers/StitchStatusReset-XML.php";
		include "../DeepZoomCreation/CreateStitchDirectories.php";
		include "../DeepZoomCreation/DeepZoomImageCompiler.php";
	}
?>			
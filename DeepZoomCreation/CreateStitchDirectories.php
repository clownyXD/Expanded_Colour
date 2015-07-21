<?php
	include_once "DeleteDir.php";
	
	if (!is_dir("../ResearchUploads/$user/Stitched")){
		mkdir("../ResearchUploads/$user/Stitched", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Stitched/$type")){
		mkdir("../ResearchUploads/$user/Stitched/$type", 0777); // forms new directories.
	}
	if (!is_dir("../ResearchUploads/$user/Stitched/$type/$layout")){
		mkdir("../ResearchUploads/$user/Stitched/$type/$layout", 0777); // forms new directories.
	}
	for ($i=50; $i<=200; $i+=50){
		if (is_dir("../ResearchUploads/$user/Stitched/$type/$layout/$i")){
			deleteDir("../ResearchUploads/$user/Stitched/$type/$layout/$i/"); //removes old directories.
		}
		mkdir("../ResearchUploads/$user/Stitched/$type/$layout/$i", 0777); // forms new directories.
	}
	if (is_dir("../ResearchUploads/$user/Stitched/$type/$layout/25")){
		deleteDir("../ResearchUploads/$user/Stitched/$type/$layout/25/"); //removes old directories.
	}
	if (is_dir("../ResearchUploads/$user/Stitched/$type/$layout/10")){
		deleteDir("../ResearchUploads/$user/Stitched/$type/$layout/10/"); //removes old directories.
	}
	mkdir("../ResearchUploads/$user/Stitched/$type/$layout/25", 0777); // forms new directories.
	mkdir("../ResearchUploads/$user/Stitched/$type/$layout/10", 0777); // forms new directories.
?>
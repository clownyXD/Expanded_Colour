<?php
	header('Content-Type: image/x-png');
	$image_name = htmlspecialchars($_GET["image_name"]);
	$user = htmlspecialchars($_GET["user"]);
	readfile ('../ResearchUploads/'.$user.'/Resized/'.$image_name);
?>

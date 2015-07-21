<html>
	<head>
		<meta charset="UTF-8" />
	</head>
	<body>
		<form action="uploading.php" method="post" enctype="multipart/form-data" onchange="function2()">
			User Number:
			<input type="text" id="user" name="user" value="2"><br>
			<input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
			
			
			<input type="submit" value="Upload!" />
		</form>
		Current uploaded images		
	</body>
	
</html>
<?php 

$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//Check if image is an actual image or not
if (isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
} // isset($_POST["submit"])

//check if file exists
if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
}

//check file size
if ($_FILES["fileToUpload"]["size"] > 4194304) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}

//Allow certain file formats
if ($imageFileType != "jpg" || $imageFileType != "jpeg" || $imageFileType != "png" || $imageFileType != "gif") {
	echo "Sorry, only JPG, JPEG, PJPEG, PNG & GIF files are allowed.";
	$uploadOk = 0;
}

//check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

 ?>
<?php
	require_once("../Classes/HttpRequest.php");
	require_once("../Classes/HttpResponse.php");
	require_once("../Classes/Database.php");
	require_once("../Classes/Images.php");
	$connection = SQLDatabase::connect();
	$Images = new Images($connection);
	$response = new HttpResponse();
	$request = new HttpRequest();

	$response->setHeaderContentTypeToJSON();

	$uploadedImage = $request->getFileValue("uploaded-image");

	if (!$uploadedImage){
		$response->jsonError("No image provided.");
	}

	// Verify the image type
	$acceptedFormats = ["JPEG", "PNG", "SVG"];
	$imagick = new Imagick($uploadedImage['tmp_name']);
	$imageFormat = $imagick->getImageFormat();
	if (array_search($imageFormat, $acceptedFormats) === false){
		$response->jsonError("Image format not supported. If you would like this format supported, contact Garet.");
	}

	$tags = $request->getPostValue("imageTags", []);

	if (!is_array($tags) || empty($tags)){
		$response->jsonError("Please provide tags for the image.");
	}

	// Remove duplicate values from $tags array
	$tags = array_unique($tags);

	// Remove blank tags
	$tags = array_filter($tags, function($value){
		return (trim($value) != "");
	});

	// Trim all tags
	array_walk($tags, function($value){
		return trim($value);
	});

	// Save the image to the file system
	$salt = uniqid(mt_rand(), true); // Generate random, unique salt
	$systemFilename = substr( md5(time() . $salt), 0, 16); // Get a 16-character version of a md5 hash. Hashed using current time + a random $salt
	$location = "/UploadedImages";
	$systemFilename = "$systemFilename." . strtolower($imageFormat); // Attach the format extension to the name
	move_uploaded_file($uploadedImage['tmp_name'], __DIR__ . "/../$location/$systemFilename");
	$Images->createImage($systemFilename, $location, $tags, $imageFormat, $imagick->getImageLength(), $imagick->getImageWidth(), $imagick->getImageHeight());

	// Make the thumbnail
	$thumbnail = clone $imagick;
	//$Images->convertToThumbnail($thumbnail, 200, 200, "center");
	$thumbnail->scaleImage(200, 0);
	file_put_contents(__DIR__ . "/../$location/thumbs/$systemFilename", $thumbnail);

	$response->jsonSuccess([]);

<?php
	require_once("../Classes/HttpRequest.php");
	require_once("../Classes/HttpResponse.php");
	require_once("../Classes/Database.php");
	require_once("../Classes/Images.php");
	$connection = SQLDatabase::connect();

	$statement = $connection->prepare("
		SELECT * FROM images
		WHERE byteSize = '0' OR width = '0' OR height='0' OR imageFormat = ''
	");
	$statement->execute();
	$result = $statement->get_result();
	$rows = $result->fetch_all(MYSQLI_ASSOC);

	foreach($rows as $badImage){
		$imagick = new Imagick(__DIR__ . "/../" . $badImage['location'] . "/" . $badImage['fileName']);
		$format = $imagick->getImageFormat();
		$size = $imagick->getImageLength();
		$width = $imagick->getImageWidth();
		$height = $imagick->getImageHeight();
		// Resave the data
		$statement = $connection->prepare("
			UPDATE images
			SET byteSize = ?, width = ?, height = ?, imageFormat = ?
			WHERE id = ?
		");
		$statement->bind_param("iiisi", $size, $width, $height, $format, $badImage['id']);
		$statement->execute();
	}

	print("Images fixed.");

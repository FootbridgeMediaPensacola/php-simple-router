<?php
	require_once("../Classes/HttpRequest.php");
	require_once("../Classes/HttpResponse.php");
	require_once("../Classes/Database.php");
	require_once("../Classes/Images.php");
	$connection = SQLDatabase::connect();
	$response = new HttpResponse();
	$request = new HttpRequest();
	$Images = new Images($connection);

	$response->setHeaderContentTypeToJSON();

	$searchQuery = '
		SELECT * FROM images WHERE id IN (
		    SELECT imageID FROM images_tags WHERE tag LIKE ?
		) OR fileName LIKE ?
	';

	$totalQuery = '
		SELECT * FROM images ORDER BY id DESC
	';

	$query = trim($request->getGetValue("query", ""));
	if ($query == ""){
		$statement = $connection->prepare($totalQuery);
	}else{
		$formattedQuery = "%$query%";
		$statement = $connection->prepare($searchQuery);
		$statement->bind_param("ss", $formattedQuery, $formattedQuery);
	}
	$statement->execute();
	$result = $statement->get_result();

	$packet = [];

	// Fetch all the results as an associative array
	if ($result->num_rows > 0){
		$packet = $result->fetch_all(MYSQLI_ASSOC);
	}

	foreach($packet as $index=>$image){
		$packet[$index]['tags'] = $Images->getTags($image['id']);
	}

	$response->jsonSuccess(["images"=>$packet]);

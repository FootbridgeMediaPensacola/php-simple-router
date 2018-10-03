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

	$query = trim($request->getGetValue("query", ""));
	$tags = preg_split('/[\ \n\,]+/', $query); // Array of all possible tags

	// Build the query clause
	$formattedQuery = "";
	foreach($tags as $index=>$tag){

		if ($index > 0){
			$formattedQuery .= "OR ";
		}

		$formattedQuery .= "tag LIKE '%" . $connection->real_escape_String($tag) . "%'";
	}

	$formattedTitleQuery = str_replace("tag LIKE", "fileName LIKE", $formattedQuery); // Format for fileName searching

	$searchQuery = "
		SELECT * FROM images WHERE id IN (
		    SELECT imageID FROM images_tags WHERE $formattedQuery
		) OR $formattedTitleQuery
	";

	$totalQuery = '
		SELECT * FROM images ORDER BY id DESC
	';

	if ($query == ""){
		$statement = $connection->prepare($totalQuery);
	}else{
		$statement = $connection->prepare($searchQuery);
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

	$response->jsonSuccess(["images"=>$packet, "formattedTags"=>$tags, "tagQuery"=>$formattedQuery]);

<?php

	class Images{

		private $dbConnection;

		public function __construct($databaseConnection){
			// Dependency injection
			$this->dbConnection = $databaseConnection;
		}

		public function createImage($fileName, $location, $tags, $format, $size, $width, $height){
			$statement = $this->dbConnection->prepare("
				INSERT INTO images
				(`location`, `fileName`, `uploadTime`, `byteSize`, `width`, `height`, `imageFormat`)
				VALUES
				(?, ?, unix_timestamp(), ?, ?, ?, ?)
			");
			$statement->bind_param("ssiiis", $location, $fileName, $size, $width, $height, $format);
			$statement->execute();
			$statement->store_result();
			$imageID = $statement->insert_id;
			$statement->free_result();

			// Tags should already be clean of all duplicates at this point
			foreach($tags as $tagName){
				$statement = $this->dbConnection->prepare("
					INSERT INTO images_tags
					(`imageID`, `tag`)
					VALUES
					(?, ?)
				");
				$statement->bind_param("is", $imageID, $tagName);
				$statement->execute();
			}
		}

		public function getTags($imageID){
			$statement = $this->dbConnection->prepare("
				SELECT tag FROM images_tags WHERE imageID = ?
			");
			$statement->bind_param("i", $imageID);
			$statement->execute();
			$result = $statement->get_result();
			$collection = [];

			if ($result->num_rows > 0){
				while ($row = $result->fetch_assoc()){
					$collection[] = $row['tag'];
				}
			}

			return $collection;
		}

		public function convertToThumbnail(&$image, $new_w, $new_h, $focus = 'center'){
			$w = $image->getImageWidth();
			$h = $image->getImageHeight();

			if ($w > $h) {
				$resize_w = $w * $new_h / $h;
				$resize_h = $new_h;
			}else{
				$resize_w = $new_w;
				$resize_h = $h * $new_w / $w;
			}
			$image->resizeImage($resize_w, $resize_h, Imagick::FILTER_LANCZOS, 0.9);

			switch ($focus) {
				case 'northwest':
					$image->cropImage($new_w, $new_h, 0, 0);
					break;

				case 'center':
					$image->cropImage($new_w, $new_h, ($resize_w - $new_w) / 2, ($resize_h - $new_h) / 2);
					break;

				case 'northeast':
					$image->cropImage($new_w, $new_h, $resize_w - $new_w, 0);
					break;

				case 'southwest':
					$image->cropImage($new_w, $new_h, 0, $resize_h - $new_h);
					break;

				case 'southeast':
					$image->cropImage($new_w, $new_h, $resize_w - $new_w, $resize_h - $new_h);
					break;
			}
		}
	}
?>

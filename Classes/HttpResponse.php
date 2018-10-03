<?php
	/*
		By Garet C. Green
		07/27/2018
	*/
	class HttpResponse{

		public function __construct(){

		}

		public function setHeaderContentTypeToJSON(){
			header("Content-Type: application/json");
		}

		public function jsonSuccess($data = []){
			print(json_encode(array_merge(
				["status"=>1],
				$data
			)));
			exit();
		}

		public function jsonError($errorMessage, $data = []){
		 	print(json_encode(array_merge(
				["status"=>-1, "error"=>$errorMessage],
				$data
			)));
			exit();
		}

	}

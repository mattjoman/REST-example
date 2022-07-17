<?php

require_once 'database.php';

function none() {
	$json = array(
		"links" => array(
			array("url" => "https://matthewpenney.net/api/comments/", "type" => "GET")
		)
	);
	echo json_encode($json);
}

function messages($method) {
	if ($method == "GET") {
		$filter = $_GET["filter"];
		if ($filter=="none") {
			$json = array(
				"links" => array(
					array("url" => "/api/", "type" => "GET"),
					array("url" => "/api/comments/", "type" => "POST"),
					array("url" => "/api/comments/past-week/", "type" => "GET"),
					array("url" => "/api/comments/today/", "type" => "GET"),
					array("url" => "/api/comments/1-day-ago/", "type" => "GET"),
					array("url" => "/api/comments/2-days-ago/", "type" => "GET"),
					array("url" => "/api/comments/3-days-ago/", "type" => "GET"),
					array("url" => "/api/comments/4-days-ago/", "type" => "GET"),
					array("url" => "/api/comments/5-days-ago/", "type" => "GET"),
					array("url" => "/api/comments/6-days-ago/", "type" => "GET")
				)
			);
			echo json_encode($json);
			exit();

		} else if ($filter=="all") {
			# GET MESSAGES FOR ALL DAYS

			$db = new Database();
			$template = "SELECT * FROM comments;";
			$params = array(); # no params
			$results = $db->getData($template, $params);

		} else {
			# GET MESSAGES FROM A CERTAIN NUMBER OF DAYS AGO (WORK IN PROGRESS)

			$day = new DateTime('NOW');
			$day->modify("-" . $filter . " day");
			$db = new Database();
			$template = "SELECT * FROM comments WHERE day_posted=?;";
			$params = array($day->format('d/m/Y'));
			$results = $db->getData($template, $params);
		}
		$json = array(
			"links" => array(
				array("url" => "https://matthewpenney.net/api/comments/", "type" => "POST"),
				array("url" => "https://matthewpenney.net/api/comments/", "type" => "GET")
			),
			"comments" => $results
		);
		echo json_encode($json);
		exit();

	} else if ($method == "POST") {
		$comment = json_decode(file_get_contents('php://input'), true)['comment'];
		$name = json_decode(file_get_contents('php://input'), true)['name'];
		$date = new DateTime('NOW');

		# no empty comments
		if ($comment == "") {exit();}
		
		if ($name == "") {
			# no name
			$name = "Anonymous";
		}
		$template = "INSERT INTO comments (comment, day_posted, name) VALUES (?, ?, ?);";
		$params = array($comment, $date->format('d/m/Y'), $name);
		$db = new Database();
		$results = $db->insertData($template, $params);
	}
}




$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
	$resource = $_GET["resource"];
} else if ($method == "POST") {
	# why $_GET and not $_POST?
	$resource = $_GET["resource"];
}


switch ($resource) {
case "none":
	none();
	break;
case "messages":
	messages($method);
	break;
}





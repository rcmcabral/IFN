<?php

	//TODO: Remove - for debugging purposes only
	error_reporting(E_ALL);
	ini_set("display_errors", "On");

	include("config.php");

	include("NodeManager.php");
	include("RoadLinkManager.php");
	include("GraphDataManager.php");
	header("Content-Type: application/json");

	$result = array();

	if (!isset($_POST["functionName"])) {
		$result["error"] = "No function name.";
	}

	if (!isset($result["error"])) {

		try {

			switch ($_POST["functionName"]) {

				case "NodeManager.ImportDataFromCSV":
					$NodeManager = new NodeManager();
					$result["result"] = $NodeManager->ImportDataFromCSV();

					break;

				case "NodeManager.DeleteAllNodes":
					$NodeManager = new NodeManager();
					$result["result"] = $NodeManager->DeleteAllNodes();

					break;

				case "RoadLinkManager.ImportDataFromCSV":
					$RoadLinkManager = new RoadLinkManager();
					$result["result"] = $RoadLinkManager->ImportDataFromCSV();

					break;

				case "RoadLinkManager.DeleteAllRoadLinks":
					$RoadLinkManager = new RoadLinkManager();
					$result["result"] = $RoadLinkManager->DeleteAllRoadLinks();

					break;

				case "GraphDataManager.GenerateJSONData":
					$GraphDataManager = new GraphDataManager();
					$result["result"] = $GraphDataManager->GenerateJSONData();

					break;

				default:
					$result["error"] = "Unrecognized function.";
			}

		} catch (Exception $ex) {
			//TODO: Proper error handling
			// throw $ex
			$result["error"] = $ex->getMessage();
		}
	}

	echo json_encode($result);
?>

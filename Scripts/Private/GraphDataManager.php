<?php

	class GraphDataManager {

		function GenerateJSONData() {

			try {

				//NOTE: This method exceeds maximum allowable memory
				// $nodes = $this->GetNodes();
				// $roadLinks = $this->GetRoadLinks();
				// $dataJSON = json_encode(array("nodes" => $nodes, "links" => $roadLinks));

				$networkName = "TestNetwork";
				// $nodesJSON = $this->GetNodesJSONData();
				$nodesJSON = "";
				$roadLinksJSON = $this->GetRoadLinksJSONData();

				//TODO: Find better way to build json data without hardcoding format
				$dataJSON = "[{ \"name\": \"$networkName\", \"nodes\": [\n".$nodesJSON."\n], \n\"links\": [\n".$roadLinksJSON."\n] }]";

				// $myfile = file_put_contents('../../Uploader/TempFiles/comprehensive_test.json', $dataJSON.PHP_EOL , LOCK_EX); //FILE_APPEND |
				$myfile = file_put_contents('../../Temp/comprehensive_test.json', $dataJSON.PHP_EOL , LOCK_EX); //FILE_APPEND |

			} catch (Exception $ex) {
				throw $ex;
			}
		}

		function GetNodesJSONData() {

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "SELECT JSON_OBJECT('id', NodeId, 'fx', Longitude, 'fy', Latitude) AS JsonData FROM Nodes");
				// $sql = mysqli_query($connection, "SELECT JSON_OBJECT('id', NodeId,
				// 	'fx', ((Longitude - 120.873602)/(121.2158393 - 120.873602))*840,
				// 	'fy', ((Latitude - 14.3008673)/(14.9385389 - 14.3008673))*480) AS JsonData FROM Nodes"); //WHERE NodeId IN (1081489216, 1081489138)

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				if ($sql->num_rows > 0) {

					$nodesJSON = "";
					while($row = $sql->fetch_assoc()) {

						if (!empty($nodesJSON)) {
							$nodesJSON .= ",\n";
						}

				    	$nodesJSON .= $row["JsonData"];
					}

					//$myfile = file_put_contents('../../Uploader/TempFiles/nodes_test.json', $nodesJSON.PHP_EOL , LOCK_EX); //FILE_APPEND |
				}

				mysqli_close($connection);

				return $nodesJSON;

			} catch (Exception $ex) {
				throw $ex;
			}

		}

		function GetRoadLinksJSONData() {

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "SELECT JSON_OBJECT(
							'source', JSON_OBJECT('id', StartNodeId, 'fx', StartNode.Longitude, 'fy', StartNode.Latitude),
							'target', JSON_OBJECT('id', EndNodeId, 'fx', EndNode.Longitude, 'fy', EndNode.Latitude)) AS JsonData
						FROM RoadLinks
						LEFT OUTER JOIN Nodes AS StartNode ON StartNode.NodeId = StartNodeId
						LEFT OUTER JOIN Nodes AS EndNode ON EndNode.NodeId = EndNodeId
						WHERE
							-- RoadType LIKE 'Motorway%'
						-- OR RoadType LIKE 'Trunk%'
						-- OR RoadType LIKE 'Primary%'
						-- OR RoadType LIKE 'Secondary%'
						-- OR RoadType LIKE 'Tertiary%'
						-- OR
						RoadType LIKE 'Unknown%'
						OR RoadType = ''
						");

				// $sql = mysqli_query($connection, "SELECT JSON_OBJECT(
				// 	'source', JSON_OBJECT('id', StartNodeId,
				// 		'fx', ((StartNode.Longitude - 120.873602)/(121.2158393 - 120.873602))*840,
				//         'fy', ((StartNode.Latitude - 14.3008673)/(14.9385389 - 14.3008673))*480),
				// 	'target', JSON_OBJECT('id', EndNodeId,
				// 		'fx', ((EndNode.Longitude - 120.873602)/(121.2158393 - 120.873602))*840,
				//         'fy', ((EndNode.Latitude - 14.3008673)/(14.9385389 - 14.3008673))*480)) AS JsonData
				// FROM RoadLinks
				// LEFT OUTER JOIN Nodes AS StartNode ON StartNode.NodeId = StartNodeId
				// LEFT OUTER JOIN Nodes AS EndNode ON EndNode.NodeId = EndNodeId"); //WHERE StartNodeId = 1081489216

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				if ($sql->num_rows > 0) {

					$roadLinksJSON = "";
					while($row = $sql->fetch_assoc()) {

						if (!empty($roadLinksJSON)) {
							$roadLinksJSON .= ",\n";
						}

						$roadLinksJSON .= $row["JsonData"];
					}

					// $myfile = file_put_contents('../../Uploader/TempFiles/roadLinks_test.json', $roadLinksJSON.PHP_EOL , LOCK_EX); //FILE_APPEND |
				}

				mysqli_close($connection);

				return $roadLinksJSON;

			} catch (Exception $ex) {
				throw $ex;
			}

		}

		//TODO: Transfer to NodeManager
		//TODO: Test if getting as json from database is faster
		function GetNodes() {

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				//$sql = mysqli_query($connection, "SELECT JSON_OBJECT('id', NodeId, 'fx', Longitude, 'fy', Latitude) AS JsonNode FROM Nodes");
				$sql = mysqli_query($connection, "SELECT NodeId AS id, Longitude AS fx, Latitude AS fy FROM Nodes");

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				$nodeArray = array();
				if ($sql->num_rows > 0) {

				    while($row = $sql->fetch_assoc()) {
				    	// $nodeArray[] = $row["JsonNode"];
				    	$nodeArray[] = $row;
					}
				}

				mysqli_close($connection);

				return $nodeArray;

			} catch (Exception $ex) {
				throw $ex;
			}

		}

		//TODO: Transfer to NodeManager
		//TODO: Test if getting as json from database is faster
		function GetNodeById($nodeId) {

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "SELECT NodeId AS id, Longitude AS fx, Latitude AS fy FROM Nodes WHERE NodeId = $nodeId LIMIT 1");

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				$row = $sql->fetch_assoc();

				return $row;

			} catch (Exception $ex) {
				throw $ex;
			}
		}

		//TODO: Transfer to RoadLinkManager
		//TODO: Test if getting as json from database is faster
		function GetRoadLinks() {

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "SELECT StartNodeId AS source, EndNodeId AS target FROM RoadLinks");

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				$roadLinkArray = array();
				if ($sql->num_rows > 0) {

				    while($row = $sql->fetch_assoc()) {
				    	$roadLinkArray[] = $row;
				    }

				}

				mysqli_close($connection);

				return $roadLinkArray;

			} catch (Exception $ex) {
				throw $ex;
			}
		}
	}

?>

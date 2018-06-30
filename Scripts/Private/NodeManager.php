<?php

	class NodeManager {

		public function ImportDataFromCSV() {

			$user = 'System';
			$remarks = '';
			$utcDate = gmdate("Y-m-d H:i:s");

			// $filePath = "../../Uploader/TempFiles/nodes-nb-mtpstu.txt";
			$filePath = "C:/Users/Rina Cabral/Documents/GitHub/PedLab/IdealFlowNetwork/SampleData/Raw/nodes-nb-mtpstu.txt";

			try {

				//NOTE: Disable secure-file-priv in mysql my.ini if not LOCAL
				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$statement = "LOAD DATA LOCAL INFILE '".$filePath."'
					INTO TABLE Nodes
					FIELDS TERMINATED BY '\t'
					LINES TERMINATED BY '\n'
					IGNORE 1 LINES
					(NodeId, Latitude, Longitude, Name)
					SET Remarks = '".$remarks."',
						Attributes = 0,
						CreatedBy = '".$user."',
						Created = '".gmdate("Y-m-d H:i:s")."',
						ModifiedBy = '".$user."',
						LastModified = '".gmdate("Y-m-d H:i:s")."'";

				$sql = mysqli_query($connection, $statement);

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				$rowsAffected = mysqli_affected_rows($connection);

				mysqli_close($connection);

				return $rowsAffected;

			} catch (Exception $ex) {
				throw $ex;
			}
		}

		// public function ImportDataFromCSV() {

		// 	try {
		// 		//TODO: get file path from uploader
		// 		$filePath = "../TempFiles/nodes-nb-mtpstu.txt";

		// 		$file = fopen($filePath, "r");
		// 		if ($file) {

		// 			// $nodes = array();

		// 			$line = fgets($file); //Ignore first line

		// 		    while (($line = fgets($file)) !== false) {

		// 	    		//Remove return characters
		// 		    	$line = str_replace("\n", "", $line);
		// 				$line = str_replace("\r", "", $line);

		// 		        $properties = preg_split("/[\t]/", $line);


		// 				$node = new stdClass();
		// 				$node->NodeId = $properties[0];
		// 				$node->Latitude = $properties[1];
		// 				$node->Longitude = $properties[2];
		// 				$node->Name = $properties[3];

		// 				$this->InsertNode($node);

		// 				//TODO: Bulk Insert
		// 				// $nodes[] = $node;
		// 		    }


		// 		    fclose($file);

		// 		} else {

		// 		    throw new Exception("Error in opening the file.");

		// 		}

		// 		// return $nodes;

		// 	} catch (Exception $ex) {
		// 		throw $ex;
		// 	}
		// }

		// public function InsertNode($node) {

		// 	$user = 'System';

		// 	try {

		// 		$DBAccess = new DBAccess();
		// 		$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);

		// 		$sql = mysqli_query($connection, "INSERT INTO Nodes Values ($node->NodeId, '".$node->Name."', $node->Latitude, $node->Longitude, '', 0, '".$user."', '".gmdate("Y-m-d H:i:s")."','".$user."', '".gmdate("Y-m-d H:i:s")."')");

		// 		if (!$sql) {
		// 			mysqli_close($connection);
		// 			throw new Exception(mysqli_error($connection));
		// 		}

		// 	} catch (Exception $ex) {
		// 		throw $ex;
		// 	}
		// }

		public function DeleteAllNodes() {
			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "DELETE FROM Nodes");

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				$rowsAffected = mysqli_affected_rows($connection);

				mysqli_close($connection);

				return $rowsAffected;

			} catch (Exception $ex) {
				throw $ex;
			}
		}
	}
?>

<?php

	class RoadLinkManager {

		public function ImportDataFromCSV() {

			$user = 'System';
			$remarks = '';
			$utcDate = gmdate("Y-m-d H:i:s");

			// $filePath = "../../Uploader/TempFiles/roadlinks-nb-mtpstu.txt";
			$filePath = "C:/Users/Rina Cabral/Documents/GitHub/PedLab/IdealFlowNetwork/SampleData/Raw/roadlinks-nb-mtpstu.txt";

			try {

				//NOTE: Disable secure-file-priv in mysql my.ini if not LOCAL
				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$statement = "LOAD DATA LOCAL INFILE '".$filePath."'
					INTO TABLE RoadLinks
					FIELDS TERMINATED BY ';'
					LINES TERMINATED BY '\n'
					IGNORE 1 LINES
					(StartNodeId, EndNodeId, Name, Distance, LaneCount, RoadType)
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

		//TODO: Transfer to RoadTypeManager
		public function GetRoadTypeId($roadTypeName) {

			switch (strtolower($roadTypeName)) {

				case 'motorway': return 1;
				case 'motorway_link': return 2;
				case 'primary': return 3;
				case 'primary_link': return 4;
				case 'secondary': return 5;
				case 'secondary_link': return 6;
				case 'tertiary': return 7;
				case 'tertiary_link': return 8;
				case 'trunk': return 9;
				case 'trunk_link': return 10;
				case 'unclassified': return 11;
				default:
					return 0;

			}
		}

		public function InsertRoadLink($roadLink) {

			$user = 'System';
			$remarks = '';
			$utcDate = gmdate("Y-m-d H:i:s");

			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "INSERT INTO RoadLinks Values (0, $roadLink->StartNodeId, $roadLink->EndNodeId, '".mysql_real_escape_string($roadLink->Name)."', $roadLink->Distance, $roadLink->LaneCount, $roadLink->RoadTypeId, '', 0, '".$user."', '".gmdate("Y-m-d H:i:s")."','".$user."', '".gmdate("Y-m-d H:i:s")."')");

				if (!$sql) {
					throw new Exception(mysqli_error($connection));
				}

				// $sql = $connection->prepare("INSERT INTO RoadLinks Values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				// $isError = $sql;
				// if ($isError === false) {
				// 	throw new Exception(mysqli_error($connection));
				// }

				// $isError = $sql->bind_param("iiisdiisissss",
				// 	$a = 0,
				// 	$roadLink->StartNodeId,
				// 	$roadLink->EndNodeId,
				// 	$roadLink->Name,
				// 	$roadLink->Distance,
				// 	$roadLink->LaneCount,
				// 	$roadLink->RoadTypeId,
				// 	$remarks, $a = 0, $user, $utcDate, $user, $utcDate);
				// if ($isError === false) {
				// 	throw new Exception(mysqli_error($connection));
				// }

				// $isError = $sql->execute();
				// if ($isError === false) {
				// 	throw new Exception(mysqli_error($connection));
				// }

				$rowsAffected = $sql->affected_rows;

				mysqli_close($connection);

				return $rowsAffected;

			} catch (Exception $ex) {
				throw $ex;
			}
		}

		public function DeleteAllRoadLinks() {
			try {

				$DBAccess = new DBAccess();
				$connection = mysqli_connect($DBAccess->DBHost, $DBAccess->DBUser, $DBAccess->DBPass, $DBAccess->DBName);
				$sql = mysqli_query($connection, "DELETE FROM RoadLinks");

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

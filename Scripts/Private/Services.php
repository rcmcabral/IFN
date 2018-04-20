<?php

  //TODO: REMOVE
  error_reporting(E_ALL);
  ini_set("display_errors","On");

  include("FileManager.php");
  header('Content-Type: application/json');

  $result = array();

  if (!isset($_POST['functionName'])) {
    $result["error"] = "No function name.";
  }

  if (!isset($result["error"])) {

    try {

      switch ($_POST["functionName"]) {
        case "FileManager.UploadFile":

          $FileManager = new FileManager();
          $result["result"] = $FileManager->UploadFile();

          break;

        case "FileManager.DeleteFile":

          if (!isset($_POST["filePath"])) {
            $result["error"] = "Missing arguments";
            break;
          }

          $FileManager = new FileManager();
          $result["result"] = $FileManager->DeleteFile($_POST["filePath"]);

          break;

        case "FileManager.ExtractData":

          if (!isset($_POST["filePath"])) {
            $result["error"] = "Missing arguments";
            break;
          }

          $FileManager = new FileManager();
          $result["result"] = $FileManager->ExtractData($_POST["filePath"]);
          break;

        case "FileManager.DownloadJSON":

          // if (!isset($_POST["data"]) || !isset($_POST["relativePath"]) || !isset($_POST["fileName"])) {
          //   $result["error"] = "Missing arguments";
          //   break;
          // }

          $FileManager = new FileManager();
          $result["result"] = $_POST["data"];// $FileManager->DownloadJSON($_POST["data"], $_POST["relativePath"], $_POST["fileName"]);
          break;

        default:
          $result["error"] = "Unrecognized function.";
          break;
      }

    } catch (Exception $ex) {
      $result["error"] = $ex;
      throw $ex;
    }

  }

  echo json_encode($result);

?>

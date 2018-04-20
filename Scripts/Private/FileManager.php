<?php

//TODO: REMOVE
error_reporting(E_ALL);
ini_set("display_errors","On");

  require("CustomUploadHandler.php");
  // require("UploadHandler.php");

  class FileManager {

    public function UploadFile() {

      $options = array(
        'relativePath' => '/Temp/Uploads/',
        'print_response' => false
      );

      // $uploadHandler = new CustomUploadHandler($options);
      $uploadHandler = new UploadHandler($options);

      return $uploadHandler;
    }

    public function DeleteFile($filePath) {

      try {

        //Append prefix to navigate to root directory
        $filePath = "../.." . $filePath;

        if (file_exists($filePath)) {
          return unlink(realPath($filePath));
        }

        return false;
      }
      catch (Exception $ex){
        throw $ex;
      }

    }

    public function ExtractData($filePath) {

      try {

        //TODO: Solve current error regarding memory allocation for large files
        //Allowed memory size of 134217728 bytes exhausted (tried to allocate 65015808 bytes)

        //Append prefix to navigate to root directory
        $filePath = "../.." . $filePath;

        $file = fopen($filePath, "r") or die("Unable to open file.");
        $rawData = fread($file, filesize($filePath));
        fclose($file);

        //TODO: Switch file handlers
        $fileType = pathInfo($filePath, PATHINFO_EXTENSION);

        if ($fileType == "json") {
          $data = $this->ExtractJSONData($rawData);
        }
        else {
          throw new Exception("Unsupported file type.");
        }

        $return = array();
        $return["filePath"] = $filePath;
        $return["fileType"] = $fileType;
        $return["size"] = filesize($filePath);
        $return["data"] = $data;
        return $return;
      }
      catch (Exception $ex){
        throw $ex;
      }
    }


    //TODO: Verify if needed, deprecate if not
    //Issue: $data is getting truncated
    public function DownloadJSON($data) {

      // try {
      //   $fileName = "download.json";
      //   $file = fopen($fileName, 'w');
      //
      //   $chunks = str_split(json_encode($data, 1024 * 4)); //Account for fwrite limit
      //   foreach ($chunks as $chunk) {
      //     fwrite($file, $chunk, strlen($chunk));
      //   }
      //   // // fclose($file);
      //   // header('Content-Type: application/json');
      //   // header('Content-Disposition: attachment; filename="'.$fileName.'";');
      //   // header('Expires: 0');
      //   // header('Cache-Control: must-revalidate');
      //   // header('Pragma: public');
      //   // header('Content-Length: ' . filesize($fileName));
      //   fpassthru($file);
      //   readfile($fileName);
      //   exit;
      //
      //
      //   // array_to_csv_download(array(
      //   //   array(1,2,3,4), // this array is going to be the first row
      //   //   array(1,2,3,4)), // this array is going to be the second row
      //   //   "numbers.csv"
      //   // );
      //
      //   return json_encode($data);
      // }
      // catch (Exception $ex){
      //   throw $ex;
      // }
    }

    private function ExtractJSONData($rawData) {

      $data = json_decode(preg_replace('/\s+/', '', $rawData), true);

      //TODO: Verify structure of json file (network, nodes, links)

      $error = json_last_error();
      if ($error == JSON_ERROR_NONE) {
        return $data;
      }

      throw new Exception($this->TranslateJSONLastError($error));
    }

    private function TranslateJSONLastError($error) {
      switch ($error) {
        case JSON_ERROR_NONE:
            return 'No errors';
        break;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            return 'Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            return 'Unknown error';
        break;
      }
    }

  }

?>

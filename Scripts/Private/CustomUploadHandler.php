<?php

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

class CustomUploadHandler extends UploadHandler {

    protected function initialize() {
    	// $this->db = new mysqli(
    	// 	$this->options['db_host'],
    	// 	$this->options['db_user'],
    	// 	$this->options['db_pass'],
    	// 	$this->options['db_name']
    	// );
        parent::initialize();
        // $this->db->close();
    }

    protected function handle_form_data($file, $index) {
    	// $file->title = @$_REQUEST['title'][$index];
    	// $file->description = @$_REQUEST['description'][$index];
      // $file->title = "";
      // $file->description = "";
    }

    //TODO: Make sql call dynamic (ie flexible number of parameters)
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = parent::handle_file_upload(
        	$uploaded_file, $name, $size, $type, $error, $index, $content_range
        );

        $relativePath = $this->getRelativePath($file->url);
        $title = $this->removeFileExtension($file->title);
        if (empty($file->error)) {
			// $sql = 'INSERT INTO `'.$this->options['db_table']
			// 	.'` (`name`, `size`, `type`, `title`, `description`, `url`)'
			// 	.' VALUES (?, ?, ?, ?, ?, ?)';
	    //     $query = $this->db->prepare($sql);
	    //     $query->bind_param(
	    //     	'sissss',
	    //     	$file->name,
	    //     	$file->size,
	    //     	$file->type,
	    //     	$file->title,
	    //     	$file->description,
      //       $file->url
	    //     );
          // $sql = "CALL ".$this->options["db_stpr"]."(?, ?, ?, ?, ?, ?, ?)";
          // $query = $this->db->prepare($sql);
          // $query->bind_param(
          //   'issssss',
          //   $this->options["aid"],
          //   $title,
          //   $file->description,
          //   $relativePath,
          //   $this->options["relativePath"],
          //   $file->name,
          //   $this->options["username"]
          // );
	        // $query->execute();
	        $file->id = $this->db->insert_id;
        }
        return $file;
    }

    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        	$sql = 'SELECT `id`, `type`, `title`, `description` FROM `'
        		.$this->options['db_table'].'` WHERE `name`=?';
        	$query = $this->db->prepare($sql);
 	        $query->bind_param('s', $file->name);
	        $query->execute();
	        $query->bind_result(
	        	$id,
	        	$type,
	        	$title,
	        	$description
	        );
	        while ($query->fetch()) {
	        	$file->id = $id;
        		$file->type = $type;
        		$file->title = $title;
        		$file->description = $description;
    		}
        }
    }

    public function delete($print_response = true) {
        $response = parent::delete(false);
        foreach ($response as $name => $deleted) {
        	if ($deleted) {
	        	$sql = 'DELETE FROM `'
	        		.$this->options['db_table'].'` WHERE `name`=?';
	        	$query = $this->db->prepare($sql);
	 	        $query->bind_param('s', $name);
		        $query->execute();
        	}
        }
        return $this->generate_response($response, $print_response);
    }

    //RINA: ADDED
    public function getRelativePath($url)
    {
      $pos = strpos($url,$this->options["relativePath"]);

      if ($pos !== false)
      {
        return substr($url, $pos);
      }

      return $url;
    }

    //RINA: ADDED
    public function removeFileExtension($name)
    {
      $fileExtension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
      $name = str_replace(".".$fileExtension, "", $name);

      $fileExtension = strtoupper($fileExtension);
      $name = str_replace(".".$fileExtension, "", $name);

      return $name;

    }
}

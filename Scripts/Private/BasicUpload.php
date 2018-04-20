<?php

error_reporting(E_ALL);
ini_set("display_errors","On");

// A list of permitted file extensions
$allowed = array('txt', 'jpg');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

    $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

    if(!in_array(strtolower($extension), $allowed)){
        echo '{"status":"file error"}';
        exit;
    }

    if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/'. $_FILES['upl']['name'])) {
        echo '{"status":"success"}';
        exit;
    }
}

echo '{"status":"internal error"}';
exit;

?>

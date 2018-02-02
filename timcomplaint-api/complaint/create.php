<?php
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");

    include_once '../config/database.php';
    
    include_once '../objects/complaint.php';
    include_once '../objects/picture.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);
    $picture = new Picture($db);

    $data = $_POST;
    
    $complaint->uid = $data["uid"];
    $complaint->status = isset($data["status"]) ? $data["status"] : 'OPEN';
    $complaint->location = $data["location"];
    $complaint->description = $data["description"];
    $complaint->category_id = $data["category_id"];
    $complaint->created = date('Y-m-d H:i:s');
    
    $newComplaintId = $complaint->create();

    if(isset($_FILES)){
        foreach ($_FILES as $FILE){

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($FILE['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )) {
                    throw new RuntimeException('Invalid file format.');
                }

                $fileNewName = sha1_file($FILE['tmp_name']);
                if (move_uploaded_file($FILE['tmp_name'],sprintf('../uploads/%s.%s',$fileNewName,$ext)
                )) {

                    $picture->uid = $complaint->uid;
                    $picture->filename = $fileNewName.'.'.$ext;
                    $picture->complaint_id = $newComplaintId;
                    $picture->created = date('Y-m-d H:i:s');;

                    $picture->create();
                }
            
        }
    }

    if($newComplaintId){
        echo '{';
            echo '"id":'.$newComplaintId;
        echo '}';
    }
    
    else{
        echo '{';
            echo '"message": "Unable to create complaint."';
        echo '}';
    }
?>
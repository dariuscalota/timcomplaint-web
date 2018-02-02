<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../objects/complaint.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $complaint->id = $data->id;
    
    $complaint->uid = $data->uid;
    $complaint->location = $data->location;
    $complaint->description = $data->description;
    $complaint->status = $data->status;
    $complaint->category_id = $data->category_id;
    
    if($complaint->update()){
        echo '{';
            echo '"success": true';
        echo '}';
    }
    
    else{
        echo '{';
            echo '"success": false';
        echo '}';
    }
?>
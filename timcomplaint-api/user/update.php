<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../objects/user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $user->id = $data->id;
    
    $user->name = $data->name;
    $user->email = $data->email;
    $user->phone = $data->phone;
    
    if($user->update()){
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
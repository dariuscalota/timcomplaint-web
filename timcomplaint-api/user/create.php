<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    
    include_once '../objects/user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $user->name = $data->name;
    $user->email = $data->email;
    $user->phone = $data->phone;
    $user->created = date('Y-m-d H:i:s');
    
    $newId = $user->create();
    if($newId){
        echo '{';
            echo '"id":'.$newId;
        echo '}';
    }
    
    else{
        echo '{';
            echo '"message": "Unable to create user."';
        echo '}';
    }
?>
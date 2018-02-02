<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    $user->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    $user->readOne();
    
    $user_arr = array(
        "id" =>  $user->id,
        "name" => $user->name,
        "email" => $user->email,
        "phone" => $user->phone
    );
    
    print_r(json_encode($user_arr));
?>
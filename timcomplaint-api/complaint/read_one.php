<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/complaint.php';
    include_once '../objects/picture.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);
    $picture = new Picture($db);
    
    $complaint->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    $complaint->readOne();
    
    $complaint_arr = array(
        "id" =>  $complaint->id,
        "uid" =>  $complaint->uid,
        "user_name" =>  $complaint->user_name,
        "location" => $complaint->location,
        "description" => $complaint->description,
        "status" => $complaint->status,
        "category_id" => $complaint->category_id,
        "category_name" => $complaint->category_name,
        "created" => $complaint->created,
        "modified" => $complaint->modified,
        "pictures" => array()
    );

    $res = $picture->getPicturesFor($complaint->id);
    $num = $res->rowCount();
    if($num>0){
        $pictures_arr["pictures"]=array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $picture_item=array(
                "id" => $id,
                "filename" => $filename,
                "created" => $created
            );
            array_push($complaint_arr["pictures"], $picture_item);
        }
    }
    
    print_r(json_encode($complaint_arr));
?>
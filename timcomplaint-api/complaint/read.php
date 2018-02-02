<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");    
    header("Access-Control-Allow-Methods: GET");
    
    include_once '../config/database.php';
    include_once '../objects/complaint.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);

    $uid = isset($_GET['uid']) ? $_GET['uid'] : "-1";
    
    $stmt = $complaint->read($uid);
    $num = $stmt->rowCount();
    
    if($num>0){
    
        $complaints_arr=array();
        $complaints_arr["records"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $complaint_item=array(
                "id" => $id,
                "uid" => $uid,
                "user_name" => $user_name,
                "location" => $location,
                "description" => html_entity_decode($description),
                "category_id" => $category_id,
                "category_name" => $category_name,
                "created" => $created,
                "status" => $status,
                "modified" => $modified
            );
    
            array_push($complaints_arr["records"], $complaint_item);
        }
    
        echo json_encode($complaints_arr);
    }
    
    else{
        echo json_encode(
            array("message" => "No complaints found.")
        );
    }

?>
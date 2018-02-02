<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../objects/complaint.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);
    
    $keywords=isset($_GET["s"]) ? $_GET["s"] : "";
    
    $stmt = $complaint->search($keywords);
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
                "pictures" => $pictures,
                "status" => $pictures,
                "category_id" => $category_id,
                "category_name" => $category_name
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
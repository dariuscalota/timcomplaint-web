<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/core.php';
    include_once '../shared/utilities.php';
    include_once '../config/database.php';
    include_once '../objects/complaint.php';
    
    $utilities = new Utilities();
    
    $database = new Database();
    $db = $database->getConnection();
    
    $complaint = new Complaint($db);
    
    $stmt = $complaint->readPaging($from_record_num, $records_per_page);
    $num = $stmt->rowCount();
    
    if($num>0){
    
        $complaints_arr=array();
        $complaints_arr["records"]=array();
        $complaints_arr["paging"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $complaint_item=array(
                "id" => $id,
                "uid" => $uid,
                "user_name" => $user_name,
                "location" => $location,
                "description" => html_entity_decode($description),
                "pictures" => $pictures,
                "status" => $status,
                "category_id" => $category_id,
                "category_name" => $category_name
            );
    
            array_push($complaints_arr["records"], $complaint_item);
        }
    
    
        $total_rows=$complaint->count();
        $page_url="{$home_url}complaint/read_paging.php?";
        $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
        $complaints_arr["paging"]=$paging;
    
        echo json_encode($complaints_arr);
    }
    
    else{
        echo json_encode(
            array("message" => "No complaints found.")
        );
    }
?>
<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../objects/report.php';
    
    $database = new Database();
    $db = $database->getConnection();

    $report = new Report($db);

    $report->date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
    $report->date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";
    $report->category = isset($_GET['category']) ? $_GET['category'] : "";
    

    $open_count = 0;
    $inprogress_count = 0;
    $solved_count = 0;
    $closed_count = 0;

    //for stats open only
    $stmt = $report->getReport();
    $num = $stmt->rowCount();
    
    $complaints_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        if($status == 'OPEN'){
            $open_count++;
        }
        if($status == 'INPROGRESS'){
            $inprogress_count++;
        }
        if($status == 'SOLVED'){
            $solved_count++;
        }
        if($status == 'CLOSED'){
            $closed_count++;
        }
    }

    echo json_encode(
        array(
            "open_count" => $open_count,
            "inprogress_count" => $inprogress_count,
            "solved_count" => $solved_count,
            "closed_count" => $closed_count
        )
    );
?>
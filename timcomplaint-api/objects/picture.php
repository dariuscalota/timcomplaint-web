<?php

class Picture{

    private $conn;
    private $table_name = "pictures";
 
    public $id;
    public $uid;
    public $complaint_id;
    public $filename;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function getPicturesFor($id){
        $query = "SELECT
                    id, filename, created
                FROM
                    " . $this->table_name . "
                WHERE
                    complaint_id = ".$id."
                ORDER BY
                    created DESC";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->execute();
    
        return $stmt;
    }


    function create() {

        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    uid=:uid, complaint_id=:complaint_id, filename=:filename, created=:created";
    
        $stmt = $this->conn->prepare($query);

        $this->filename=htmlspecialchars(strip_tags($this->filename));
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->complaint_id=htmlspecialchars(strip_tags($this->complaint_id));
        $this->created=htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":filename", $this->filename);
        $stmt->bindParam(":uid", $this->uid);
        $stmt->bindParam(":complaint_id", $this->complaint_id);
        $stmt->bindParam(":created", $this->created);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

            
    }
    
}

?>
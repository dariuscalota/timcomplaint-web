<?php

class Complaint{
 
    private $conn;
    private $table_name = "complaints";
 
    public $id;
    public $uid;
    public $user_name;
    public $location;
    public $description;
    public $category_id;
    public $category_name;
    public $created;
    public $modified;
    public $status;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function readOne(){
    
        $query = 'SELECT
                    c.name as category_name, u.name as user_name, p.id, p.uid, p.location, p.description, p.category_id, p.created, p.modified, p.status
                FROM
                    ' . $this->table_name . ' p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                    LEFT JOIN
                        users u
                            ON p.uid = u.id
                WHERE
                    p.id = ?
                    AND
                    status <> "DELETED"
                LIMIT
                    0,1';
    
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(1, $this->id);

        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->uid = $row['uid'];
        $this->user_name = $row['user_name'];
        $this->location = $row['location'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->modified = $row['modified'];
        $this->status = $row['status'];
        $this->created = $row['created'];
    }

    function read($uid){
        $filter='WHERE  status <> "DELETED"';
        if($uid != -1){
            $filter = 'WHERE (uid="'.$uid.'" AND status <> "DELETED")';
        }
        $query = "SELECT
                    c.name as category_name, u.name as user_name, p.id, p.uid, p.description, p.location, p.category_id, p.created, p.modified, p.status
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                    LEFT JOIN
                        users u
                            ON p.uid = u.id
                    ".$filter."
                ORDER BY
                    p.created DESC";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->execute();
    
        return $stmt;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    uid=:uid, location=:location, description=:description, category_id=:category_id, created=:created, status=:status";
    
        $stmt = $this->conn->prepare($query);
    
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        $stmt->bindParam(":uid", $this->uid);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);
    
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }
    }

    function update(){
    
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    uid = :uid,
                    location = :location,
                    description = :description,
                    category_id = :category_id,
                    status = :status
                WHERE
                    id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->status=htmlspecialchars(strip_tags($this->status));
    
        $stmt->bindParam(':uid', $this->uid);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
    
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete(){
        $newstatus = "DELETED";
        $query = "UPDATE
                " . $this->table_name . "
            SET
                status = :status
            WHERE
                id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        $stmt->bindParam(':status', $newstatus);
        $stmt->bindParam(':id', $this->id);
    
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    
    function search($keywords){
    
        $query = "SELECT
                    c.name as category_name, u.name as user_name, p.id, p.uid, p.description, p.location, p.category_id, p.created, p.status
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                    LEFT JOIN
                        users u
                            ON p.uid = u.id
                WHERE
                    p.description LIKE ? OR c.name LIKE ? OR u.name LIKE ?
                ORDER BY
                    p.created DESC";
    
        $stmt = $this->conn->prepare($query);
    
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        $stmt->execute();
    
        return $stmt;
    }

    public function readPaging($from_record_num, $records_per_page){
    
        $query = "SELECT
                    c.name as category_name, u.name as user_name, p.id, p.uid, p.location, p.description, p.category_id, p.created, p.status
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                    LEFT JOIN
                        users u
                            ON p.uid = u.id
                ORDER BY p.created DESC
                LIMIT ?, ?";
    
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt;
    }

    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }
}

?>
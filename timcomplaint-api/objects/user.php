<?php
class User{
 
    private $conn;
    private $table_name = "users";
 
    public $id;
    public $name;
    public $email;
    public $phone;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function readOne(){
    
        $query = "SELECT
                    p.id, p.name, p.email, p.phone, p.created
                FROM
                    " . $this->table_name . " p
                WHERE
                    p.id = ?
                LIMIT
                    0,1";
    
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(1, $this->id);
    
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
    }
 
    function read(){

        $query = "SELECT
                    p.id, p.name, p.email, p.phone, p.created
                FROM
                    " . $this->table_name . " p
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
                    name=:name, email=:email, phone=:phone, created=:created";
    
        $stmt = $this->conn->prepare($query);
    
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
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
                    name = :name,
                    email = :email,
                    phone = :phone
                WHERE
                    id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $this->id);
    
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete(){
    
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        $stmt = $this->conn->prepare($query);
    
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        $stmt->bindParam(1, $this->id);
    
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    

 
}
?>
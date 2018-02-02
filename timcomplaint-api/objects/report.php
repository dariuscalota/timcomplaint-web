<?php
class Report{
 
    private $conn;
    private $table_name = "complaints";
 
    public $date_from;
    public $date_to;
    public $category;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    public function getReport(){

        $filters="";

        if(strlen($this->date_from)>0 && strlen($this->date_to)>0){
            $filters = "WHERE created >= '".$this->date_from."' AND created <= '".$this->date_to."'";
        } else if(strlen($this->date_from)>0 && strlen($this->date_to)==0){
            $filters = "WHERE created >= '".$this->date_from."'";
        } else if(strlen($this->date_to)>0 && strlen($this->date_from)==0){
            $filters = "WHERE created <= '".$this->date_to."'";
        }

        if(strlen($this->category)>0){
            if(strlen($filters) == 0){
                $filters.= " WHERE category_id = ".$this->category;
            } else {
                $filters.= " AND category_id = ".$this->category;
            }
        }

        $query = "SELECT
                    *
                FROM
                    complaints
                " . $filters . "
                ORDER BY
                    status";
 
        $stmt = $this->conn->prepare( $query );

        $this->date_from=htmlspecialchars(strip_tags($this->date_from));
        $this->date_to=htmlspecialchars(strip_tags($this->date_to));
        $this->category=htmlspecialchars(strip_tags($this->category));

        // $stmt->bindParam(":date_from", $this->date_from);
        // $stmt->bindParam(":date_to", $this->date_to);
        // $stmt->bindParam(":category", $this->category);

        $stmt->execute();
 
        return $stmt;
    }
 
}
?>
<?php

class Model{
    protected static $conn;
    protected static $table = 'products';
    protected static $primaryKey = 'id';

    public static function setConnection($conn){
        self::$conn = $conn;
    }

    protected static function all(){
        try{
            $sql = "SELECT * from " . static::$table;
            $stmt = self::$conn->query($sql);
            $rows =  $stmt->fetchAll(); 
            
            return count($rows) > 0 ? $rows : null; 
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

    protected static function find($id){
        try{
            $pk = static::$primaryKey;
            $sql = "SELECT * from " . static::$table . " WHERE $pk = :id";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':id', $id); 
            $stmt->execute(); 
            $row =  $stmt->fetch(); 
            
            if(empty($row)){
                return null; 
            }
            else{
                return count($row) > 0 ? $row : null;
            }
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

    protected static function create(array $data){
        try{
            $columns = implode(", ", array_keys($data));
            $values = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));
            $sql = "INSERT INTO " . static::$table
                . " ($columns) VALUES ($values)";

            $stmt = self::$conn->prepare($sql);

            foreach($data as $key => $value){ 
                $stmt->bindValue(":$key", $value);     
            }

            $stmt->execute(); 

            $id = self::$conn->lastInsertId(); 
            return self::find($id); 
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

    protected static function updateById($id, array $data){
        try{
            $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $pk = static::$primaryKey;

            $sql = "UPDATE " . static::$table
                . " SET $set WHERE $pk = :id";
            
            $stmt = self::$conn->prepare($sql);

            foreach($data as $key => $value){ 
                $stmt->bindValue(":$key", $value); 
            }
            $stmt->bindValue(':id', $id);
            $stmt->execute(); 

            return self::find($id);
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

    protected static function deleteById($id){
        try{
            $pk = static::$primaryKey;
            $sql = "DELETE FROM " . static::$table
            . " WHERE $pk = :id";

            $stmt = self::$conn->prepare($sql);
            $stmt->bindValue(':id', $id); 
            $stmt->execute(); 
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

    protected static function where($column, $operator, $value){
        try{
            $sql = "SELECT * FROM " . static::$table . " WHERE $column $operator :value"; 

            $stmt = self::$conn->prepare($sql);

            $stmt->bindValue(':value', $value);

            $stmt->execute(); 

            $rows =  $stmt->fetchAll(); 

            return count($rows) > 0 ? $rows : null;
        }
        catch(PDOException $e){
            die("Query failes: " . $e->getMessage());
        }
    }

    protected function belongsToMany($relatedClass, $pivotTable, $foreignKey, $relatedKey){
        try{
            $relatedTable = $relatedClass::$table;
            $sql = "SELECT rt.* FROM $relatedTable rt INNER JOIN $pivotTable pt ON rt.id = pt.$relatedKey WHERE pt.$foreignKey = :id";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindValue(':id', $this->id);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            return $rows ? array_map(fn($row) => new $relatedClass($row), $rows) : null;
        }
        catch(PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    }

}

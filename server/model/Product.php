<?php
class Product
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
    
    public function getProductByIdAndStock($id, $quantity)
    {
        $stmt = $this->conn->prepare("SELECT id AS product_id, name AS product_name, stock, price  FROM products WHERE id = :id AND stock >= :quantity;");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function getProductById($id){
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsByFilters($joinQuery, $filterQuery)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products". $joinQuery . $filterQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTableName($subcategory)
    {
        $tableName = '';
        if ($subcategory == 'mouse') $tableName = 'mice';
        else $tableName = strtolower($subcategory). 's';

        try {
            $stmt = $this->conn->prepare("SELECT * FROM products JOIN ".$tableName);
            if ($stmt->execute()) return $tableName;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }
}

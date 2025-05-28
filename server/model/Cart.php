<?php

class Cart
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId = null)
    {

        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function getUserItems()
    {

        if (!$this->userId) return [];

        $stmt = $this->conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) return [];

        $cartId = $cart['id'];


        $stmt = $this->conn->prepare("
            SELECT 
                ci.id AS cart_item_id, 
                ci.product_id, 
                ci.quantity,
                description,
                p.image_url,
                p.name AS product_name,
                p.price
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ");

        $stmt->execute([$cartId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCartItems($userId)
    {

        $stmt = $this->conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            return false;
        }

        $cartId = $cart['id'];


        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        return $stmt->execute([$cartId]);
    }

    public function getOrCreateCart($userId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return $cart['id'];
        }

        $stmt = $this->conn->prepare("INSERT INTO shopping_cart (user_id, created_at) VALUES (?, NOW())");
        $stmt->execute([$userId]);

        return $this->conn->lastInsertId();
    }

    function addSavedItemToCart($userId, $productId, $quantity)
    {

        $stmt = $this->conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cartId = $stmt->fetchColumn();

        if (!$cartId) {
            $stmtCreate = $this->conn->prepare("INSERT INTO shopping_cart (user_id, created_at) VALUES (?, NOW())");
            $stmtCreate->execute([$userId]);
            $cartId = $this->conn->lastInsertId();
        }

        $stmtCheck = $this->conn->prepare("SELECT quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmtCheck->execute([$cartId, $productId]);
        $existingQuantity = $stmtCheck->fetchColumn();

        if ($existingQuantity !== false) {
            $newQuantity = $existingQuantity + $quantity;
            $stmtUpdate = $this->conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?");
            return $stmtUpdate->execute([$newQuantity, $cartId, $productId]);
        } else {
            $stmtInsert = $this->conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmtInsert->execute([$cartId, $productId, $quantity]);
        }
    }

    function removeCartItemById($userId, $cartItemId)
    {

        $sql = "
        DELETE ci
        FROM cart_items ci
        INNER JOIN shopping_cart sc ON ci.cart_id = sc.id
        WHERE ci.id = ? AND sc.user_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cartItemId, $userId]);
    }
}

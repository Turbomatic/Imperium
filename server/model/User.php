<?php

class User
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
    // Authentication and Identify functions
    public function login($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEmailByid($id)
    {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function registerGoogleUser($googleUserData)
    {

        // 1. Check if user already exists
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE google_id = :google_id');
        $stmt->execute([':google_id' => $googleUserData['id']]);
        $user = $stmt->fetch();

        if ($user) {
            // Already exists — maybe update name/email/avatar if changed
            $stmt = $this->conn->prepare('
                UPDATE users 
                SET name = :name, email = :email, avatar_url = :avatar_url, updated_at = NOW()
                WHERE google_id = :google_id
            ');
            $stmt->execute([
                ':name' => $googleUserData['name'],
                ':email' => $googleUserData['email'],
                ':avatar_url' => $googleUserData['picture'],
                ':google_id' => $googleUserData['id'],
            ]);
            return $user['id']; // return existing user id
        } else {
            // New Google user — insert
            $stmt = $this->conn->prepare('
                INSERT INTO users (google_id, name, email, avatar_url, created_at, updated_at)
                VALUES (:google_id, :name, :email, :avatar_url, NOW(), NOW())
            ');
            $stmt->execute([
                ':google_id' => $googleUserData['id'],
                ':name' => $googleUserData['name'],
                ':email' => $googleUserData['email'],
                ':avatar_url' => $googleUserData['picture'],
            ]);
            return $this->conn->lastInsertId(); // new user id
        }
    }

    public function checkEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($name, $surname, $email, $password)
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $surname, $email, $hashed]);
    }
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUser($id)
    {
        $stmt = $this->conn->prepare("SELECT name , email, created_at FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function generateRecoveryToken($email, $code)
    {
        $expiry = date("Y-m-d H-i-s", time() + 60 * 3); //current time plus 3 minutes
        $stmt = $this->conn->prepare("UPDATE users SET resetCode = ?, resetCodeExpiry = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([$code, $expiry, null, $email]);
    }
    public function approveResetCode($email)
    {
        $expiry = date("Y-m-d H-i-s", time() + 60 * 3); //current time plus 3 minutes
        $stmt = $this->conn->prepare("UPDATE users SET resetCode = ?, resetCodeExpiry = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([null, null, $expiry, $email]);
    }
    public function updatePasswordFromRecovery($email, $password)
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([$hashed, null, $email]);
    }

    public function getUserByGoogleId($googleId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE google_id = :google_id");
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function verifyPassword($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }


    //Profile Menagement functions

    public function updateUser($userId, $newData)
    {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentData) {
            return false;
        }

        $fieldsToUpdate = [];
        $params = ['userId' => $userId];

        foreach ($newData as $key => $value) {
            if (array_key_exists($key, $currentData) && $currentData[$key] !== $value) {
                $fieldsToUpdate[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fieldsToUpdate)) {
            return false;
        }


        $sql = "UPDATE users SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :userId";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }


    public function changePassword($userId, $oldPass, $newPass)
    {
        // Get user
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId);
        //$stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($oldPass, $user['password'])) {
            return false; // wrong current password
        }

        // Update password
        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$newHash, $userId]);
    }

    public function getOrderHistory($userId)
    {

        $stmt = $this->conn->prepare("
            SELECT 
                o.id AS order_id,
                p.name AS product_name,
                p.image_url,
                p.description,
                oi.price,
                o.status AS action,
                o.created_at
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = :userId
            ORDER BY o.created_at DESC");

        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSavedItems($userId)
    {

        $sql = "
            SELECT 
                p.id AS product_id,
                p.name AS product_name,
                p.description,
                p.price,
                p.image_url,
                si.saved_at
            FROM saved_items si
            JOIN products p ON si.product_id = p.id
            WHERE si.user_id = :userId
            ORDER BY si.saved_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeSavedItem($userId, $itemId)
    {

        $stmt = $this->conn->prepare("DELETE FROM saved_items WHERE user_id = :userId AND product_id = :itemId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function deleteUser($userId)
    {

        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function addProductToSavedItems($userId, $productId)
    {
        $stmt = $this->conn->prepare("INSERT INTO saved_items (user_id, product_id, saved_at) VALUES (:userId, :productId, NOW())");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

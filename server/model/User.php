<?php
class User
{
    private $conn;

    public function __construct()
    {
        require '../../config/db.php';
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
    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function generateRecoveryToken($email, $code) {
        $expiry = date("Y-m-d H-i-s", time() + 60 * 3); //current time plus 3 minutes
        $stmt = $this->conn->prepare("UPDATE users SET resetCode = ?, resetCodeExpiry = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([$code, $expiry, null, $email]);
    }
    public function approveResetCode($email) {
        $expiry = date("Y-m-d H-i-s", time() + 60 * 3); //current time plus 3 minutes
        $stmt = $this->conn->prepare("UPDATE users SET resetCode = ?, resetCodeExpiry = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([null, null, $expiry, $email]);
    }
    public function updatePasswordByEmail($email, $password) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, newPasswordExpiry = ? WHERE email = ?");
        return $stmt->execute([$hashed, null, $email]);
    }

    public function getUserByGoogleId($googleId) {
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
    public function updatePassword($userId, $newPassword)
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed, $userId]);
    }

    

    //Profile Menagement functions

    public function updateUser($userId, $newData)
    {
        // Step 1: Get current data
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentData) {
            return false; // user not found
        }

        // Step 2: Prepare update fields
        $fieldsToUpdate = [];
        $params = [];

        foreach ($newData as $key => $value) {
            // Only update if the field exists in current data AND has changed
            if (array_key_exists($key, $currentData) && $currentData[$key] !== $value) {
                $fieldsToUpdate[] = "$key = ?";
                $params[] = $value;
            }
        }

        // Step 3: Add user ID to params
        if (empty($fieldsToUpdate)) {
            return false; 
        }

        $params[] = $userId;

        // Step 4: Build and execute SQL
        $sql = "UPDATE users SET " . implode(', ', $fieldsToUpdate) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function changePassword($userId, $oldPass, $newPass){
    // Get user
    $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($oldPass, $user['password'])) {
        return false; // wrong current password
    }

    // Update password
    $newHash = password_hash($newPass, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$newHash, $userId]);
    }
}

<?php
require_once './database.config.php';

try {
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}

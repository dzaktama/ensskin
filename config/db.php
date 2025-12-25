<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ensskin_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Helper untuk fetch banyak data
function fetchAll($query, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Helper untuk fetch satu data
function fetchOne($query, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Helper untuk eksekusi query (Insert/Update/Delete)
function executeQuery($query, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}
?>
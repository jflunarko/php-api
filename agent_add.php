<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['name'], $input['email'], $input['password'], $input['status'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$name = mysqli_real_escape_string($conn, $input['name']);
$email = mysqli_real_escape_string($conn, $input['email']);
$password = mysqli_real_escape_string($conn, $input['password']);
$status = mysqli_real_escape_string($conn, $input['status']);

$sql = "INSERT INTO agent (name, email, password, status, created_at, updated_at) 
        VALUES ('$name', '$email', '$password', '$status', NOW(), NOW())";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Agent added successfully', 'id' => mysqli_insert_id($conn)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add agent']);
}

mysqli_close($conn);
?>

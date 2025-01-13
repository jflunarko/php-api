<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['agent_id'], $input['name'], $input['street'], $input['price'], $input['category'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$agent_id = (int)$input['agent_id'];
$name = mysqli_real_escape_string($conn, $input['name']);
$street = mysqli_real_escape_string($conn, $input['street']);
$price = mysqli_real_escape_string($conn, $input['price']);
$category = mysqli_real_escape_string($conn, $input['category']);

$sql = "INSERT INTO listing (agent_id, name, street, price, category, created_at, updated_at) 
        VALUES ('$agent_id', '$name', '$street', '$price', '$category', NOW(), NOW())";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Listing created successfully', 'id' => mysqli_insert_id($conn)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create listing']);
}

mysqli_close($conn);
?>

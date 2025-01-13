<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

$id = (int)$input['id'];

$name = isset($input['name']) ? mysqli_real_escape_string($conn, $input['name']) : null;
$email = isset($input['email']) ? mysqli_real_escape_string($conn, $input['email']) : null;
$status = isset($input['status']) ? mysqli_real_escape_string($conn, $input['status']) : null;

$updateFields = [];
if ($name !== null) $updateFields[] = "name = '$name'";
if ($email !== null) $updateFields[] = "email = '$email'";
if ($status !== null) $updateFields[] = "status = '$status'";

if (empty($updateFields)) {
    echo json_encode(['success' => false, 'message' => 'No fields to update']);
    exit;
}

$updateQuery = implode(', ', $updateFields);

$sql = "UPDATE agent SET $updateQuery, updated_at = NOW() WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Agent updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update agent']);
}

mysqli_close($conn);
?>
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,  OPTIONS");
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
$street = isset($input['street']) ? mysqli_real_escape_string($conn, $input['street']) : null;
$price = isset($input['price']) ? mysqli_real_escape_string($conn, $input['price']) : null;
$category = isset($input['category']) ? mysqli_real_escape_string($conn, $input['category']) : null;

$updateFields = [];
if ($name !== null) $updateFields[] = "name = '$name'";
if ($street !== null) $updateFields[] = "street = '$street'";
if ($price !== null) $updateFields[] = "price = '$price'";
if ($category !== null) $updateFields[] = "category = '$category'";

if (empty($updateFields)) {
    echo json_encode(['success' => false, 'message' => 'No fields to update']);
    exit;
}

$updateQuery = implode(', ', $updateFields);

$sql = "UPDATE listing SET $updateQuery, updated_at = NOW() WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Listing updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update listing']);
}

mysqli_close($conn);
?>

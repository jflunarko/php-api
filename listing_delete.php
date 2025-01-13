<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID listing is required']);
    exit;
}

$listingId = (int)$input['id'];

$sql = "DELETE FROM listing WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $listingId);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Listing deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete listing']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

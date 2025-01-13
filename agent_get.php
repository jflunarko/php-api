<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php';

$searchName = isset($_GET['name']) ? $_GET['name'] : '';

if ($searchName) {
    $searchName = mysqli_real_escape_string($conn, $searchName);
    $sql = "SELECT * FROM agent WHERE name LIKE '%$searchName%'";
} else {
    $sql = "SELECT * FROM agent";
}

$result = mysqli_query($conn, $sql);

$response = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = [
            'id' => (int)$row['id'], 
            'name' => $row['name'] ?? '', 
            'email' => $row['email'] ?? '',
            'password' => $row['password'] ?? '',
            'status' => $row['status'] ?? '',
            'created_at' => $row['created_at'] ?? '',
            'updated_at' => $row['updated_at'] ?? '',
        ];
    }
}

echo json_encode(['agents' => $response]);

mysqli_close($conn);
?>
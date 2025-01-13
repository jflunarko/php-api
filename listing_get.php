<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php'; 
$searchName = isset($_GET['name']) ? $_GET['name'] : '';

try {
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    if ($searchName) {
        $sql = "SELECT * FROM listing WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchParam = "%" . $searchName . "%";
        $stmt->bind_param("s", $searchParam);
    } else {
        $sql = "SELECT * FROM listing";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $response = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'id' => (int)$row['id'],
                'agent_id' => (int)$row['agent_id'],
                'name' => $row['name'] ?? '',
                'street' => $row['street'] ?? '',
                'price' => $row['price'] ?? '',
                'category' => $row['category'] ?? '',
                'created_at' => $row['created_at'] ?? '',
                'updated_at' => $row['updated_at'] ?? '',
            ];
        }
    }
    echo json_encode(['listings' => $response]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}

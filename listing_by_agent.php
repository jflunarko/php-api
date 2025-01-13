<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php'; 
$agentId = isset($_GET['agent_id']) ? (int)$_GET['agent_id'] : 0;

try {
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    if ($agentId <= 0) {
        throw new Exception("Invalid agent_id provided.");
    }
    $sql = "SELECT * FROM listing WHERE agent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $agentId);
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

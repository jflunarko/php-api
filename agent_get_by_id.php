<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include 'database.php'; 

$agentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    if ($agentId <= 0) {
        throw new Exception("Invalid agent ID");
    }

    $sql = "SELECT * FROM agent WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $agentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['agent' => $row]);
    } else {
        echo json_encode(['error' => 'Agent not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>

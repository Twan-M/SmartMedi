<?php
header("Content-Type: application/json");
include 'php/db.php';

// Check if the request is a GET request and the necessary data is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name']) && isset($_GET['supply'])) {
    // Retrieve and sanitize input values
    $name = htmlspecialchars($_GET['name']);
    $supply = intval($_GET['supply']);
    
    // Prepare the UPDATE statement
    $sql = "UPDATE medication SET supply = ? WHERE name = ?";
    $stmt = $conn->prepare($sql);
    
    // Correct parameter binding: 'i' for integer (supply), 's' for string (name)
    $stmt->bind_param("is", $supply, $name);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Output a JSON success message
        echo json_encode(["success" => true, "message" => "Medication updated successfully"]);
    } else {
        // Output a JSON error message
        echo json_encode(["success" => false, "message" => "Error updating medication: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request. Name and supply are required as URL parameters."]);
}

$conn->close();
?>
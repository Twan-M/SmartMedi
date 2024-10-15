<?php
include 'php/db.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Retrieve and sanitize input values
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $supply = intval($_POST['supply']);
    $max = intval($_POST['max']);
    $drawer = intval($_POST['drawer']);
    
    // Prepare the UPDATE statement
    $sql = "UPDATE medication SET name = ?, supply = ?, max = ?, drawer = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiii", $name, $supply, $max, $drawer, $id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the main page with a success message
        header("Location: index.php?message=Medication+updated+successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>

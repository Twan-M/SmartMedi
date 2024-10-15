<?php
include 'php/db.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Make sure it's an integer to prevent SQL injection

    // Prepare the DELETE statement
    $sql = "DELETE FROM medication WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the main page with a success message
        header("Location: index.php?message=Medication+deleted+successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No medication ID provided.";
}

$conn->close();
?>
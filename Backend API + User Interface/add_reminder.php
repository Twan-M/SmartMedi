<?php
include 'php/db.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input values
    $name = htmlspecialchars($_POST['name']);
    $time = htmlspecialchars($_POST['time']);
    $interval = htmlspecialchars($_POST['interval']); // Changed name to match your form
    
    // Prepare the INSERT statement
    $sql = "INSERT INTO reminders (`name`, `interval`, `frequency`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $interval, $time);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the main page with a success message
        header("Location: index.php?message=Reminder+added+successfully");
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
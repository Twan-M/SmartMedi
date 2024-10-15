<?php
include 'php/db.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input values
    $name = htmlspecialchars($_POST['name']);
    $time = htmlspecialchars($_POST['supply']);
    $interval = htmlspecialchars($_POST['max']); 
    $drawer = htmlspecialchars($_POST['drawer']);
    
    // Prepare the INSERT statement
    $sql = "INSERT INTO medication (`name`, `supply`, `max`, `drawer`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $time, $interval, $drawer);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the main page with a success message
        header("Location: index.php?message=Medication+added+successfully");
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

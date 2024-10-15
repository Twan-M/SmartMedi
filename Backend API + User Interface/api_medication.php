<?php
header("Content-Type: application/json");

include 'php/db.php';

// Query to fetch all reminders
$sql = "SELECT id, name, supply, max, drawer FROM medication";
$result = $conn->query($sql);

$reminders = [];

if ($result->num_rows > 0) {
    // Fetch each row and add to the reminders array
    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row;
    }
}

// Return the data in JSON format
echo json_encode($reminders);

$conn->close();
?>
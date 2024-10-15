<?php
header("Content-Type: application/json");
include 'php/db.php';

// Controleer of de medicijnnaam is opgegeven
if (isset($_GET['medication'])) {
    $medication = htmlspecialchars($_GET['medication']);

    // Query de database voor de voorraad van het medicijn
    $sql = "SELECT supply FROM medication WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $medication);
    $stmt->execute();
    $stmt->bind_result($supply);
    $stmt->fetch();
    
    if ($supply !== null) {
        echo json_encode(["success" => true, "supply" => $supply]);
    } else {
        echo json_encode(["success" => false, "message" => "Medicijn niet gevonden."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Geen medicijnnaam opgegeven."]);
}

$conn->close();
?>
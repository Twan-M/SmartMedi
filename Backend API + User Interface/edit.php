<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SmartMedi</title>
  <link rel="stylesheet" href="static/style.css">
</head>
<body>

<?php
include 'php/db.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID to prevent SQL injection

    // Fetch the medication details
    $sql = "SELECT name, supply, max, drawer FROM medication WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $supply, $max, $drawer);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "No medication ID provided.";
    exit();
}

$conn->close();
?>

<div class="row">
  <div class="col-md-12">
    <form action="update_medication.php" method="post">
      <h1> SmartMedi Dashboard </h1>
      
      <!-- Edit Medication Form -->
      <fieldset>  
        <legend><span class="number">1</span> Edit medication</legend>
        
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="name">Medication name:</label>
        <input required type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">

        <label for="supply">Supply:</label>
        <input required type="number" id="supply" name="supply" value="<?php echo htmlspecialchars($supply); ?>" min="0">

        <label for="max">Max supply:</label>
        <input required type="number" id="max" name="max" value="<?php echo htmlspecialchars($max); ?>" min="1">

        <label for="max">Drawer:</label>
        <input required type="number" id="drawer" name="drawer" value="<?php echo htmlspecialchars($drawer); ?>" min="1">
        
      </fieldset>

      <button type="submit">Save</button>
      <button type="button" class="button-red" onclick="window.location.href='/delete_medication.php?id=<?php echo $id; ?>';">Delete Medications</button>
      
    </form>
  </div>
</div>

</body>
</html>

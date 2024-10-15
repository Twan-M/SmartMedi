<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>SmartMedi</title>
  <link rel="stylesheet" href="static/style.css">

</head>
<body>
<!-- partial:index.partial.html -->

<?php
include 'php/db.php';

// Query to fetch medications
$sql_medications = "SELECT id, name, supply, max, drawer FROM medication";
$result_medications = $conn->query($sql_medications);

$medications_data = [];
if ($result_medications->num_rows > 0) {
    while($row = $result_medications->fetch_assoc()) {
        $medications_data[] = $row;
    }
} else {
    echo "<p>No data found in medication table.</p>";
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SmartMedi</title>
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
      <div class="row">
    <div class="col-md-12">
      <form action="add_medication.php" method="post">
        <h1> SmartMedi Dashboard </h1>
        <fieldset>
        <legend><span class="number">1</span> Edit medications</legend>
        <table style="width:100%">
          <thead>
            <tr>
              <th>Medication</th>
              <th>Current Supply</th>
              <th>Max Supply</th>
              <th>Drawer</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($medications_data)): ?>
              <?php foreach ($medications_data as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row["name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["supply"]); ?></td>
                  <td><?php echo htmlspecialchars($row["max"]); ?></td>
                  <td><?php echo htmlspecialchars($row["drawer"]); ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>">
                      <img src="static/pen-to-square-solid.svg" alt="Edit" class="icon-edit" style="width:16px; height:16px;">
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">No medications found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </fieldset>

        <fieldset>  
        
          <legend><span class="number">2</span> Add medications</legend>
          
         <label for="name">Medication name:</label>
         <input required type="text" id="name" name="name">
        
         <label for="name">Supply:</label>
         <input required type="text" id="supply" name="supply">
        
         <label for="name">Max supply:</label>
         <input required type="text" id="max" name="max">

         <label for="name">Drawer:</label>
         <input required type="text" id="drawer" name="drawer">
          
         </fieldset>
         <button type="submit">Add medication</button>
        
       </form>
        </div>
      </div>
      
    </body>
</html>
  
</body>
</html>

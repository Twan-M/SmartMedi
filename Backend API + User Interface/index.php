<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SmartMedi</title>
  <link rel="stylesheet" href="static/style.css">
  <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
</head>
<body>
<?php
include 'php/db.php';

// Query to fetch reminders
$sql_reminders = "SELECT id, `interval`, frequency, name FROM reminders";
$result_reminders = $conn->query($sql_reminders);

$reminders_data = [];
if ($result_reminders->num_rows > 0) {
    while($row = $result_reminders->fetch_assoc()) {
        $reminders_data[] = $row;
    }
} else {
    echo "<p>No data found in reminders table.</p>";
}

// Query to fetch medications
$sql_medications = "SELECT name, supply, max, drawer FROM medication";
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

<div class="row">
  <div class="col-md-12">
    <form action="index.php" method="post">
      <h1> SmartMedi Dashboard </h1>

      <!-- Reminders Table -->
      <fieldset>
        <legend><span class="number">1</span> Current reminders:</legend>
        <?php if (isset($_GET['message'])): ?>
  <p><?php echo htmlspecialchars($_GET['message']); ?></p>
<?php endif; ?>
        <table style="width:100%">
          <thead>
            <tr>
              <th>Medication</th>
              <th>(Start)Time</th>
              <th>Interval</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($reminders_data)): ?>
              <?php foreach ($reminders_data as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row["name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["frequency"]); ?></td>
                  <td><?php echo htmlspecialchars($row["interval"]); ?></td>
                  <td>
                    <a href="delete_reminder.php?id=<?php echo $row['id']; ?>">
                      <img src="static/trash-solid.svg" alt="Delete" class="icon-trash">
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">No reminders found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </fieldset>

      <!-- Medications Table -->
      <fieldset>
  <legend><span class="number">2</span> Current medicine supply</legend>
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
              <?php if ($row["supply"] > 5): ?>
                <img src="static/check-solid.svg" alt="Check" class="icon-check">
              <?php else: ?>
                <img src="static/triangle-exclamation-solid.svg" alt="Warning" class="icon-danger">
              <?php endif; ?>
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

      <button type="button" onclick="window.location.href='/reminders.php';">Add Reminder</button>
      <button type="button" class="button-alt" onclick="window.location.href='/medication.php';">Manage Medications</button>
      
    </form>
  </div>
</div>

<?php $conn->close(); ?>

</body>
</html>

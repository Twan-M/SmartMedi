<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>SmartMedi</title>
  <link rel="stylesheet" href="static/style.css">

</head>
<body>
<!-- partial:index.partial.html -->
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
      <form action="add_reminder.php" method="post">
        <h1> SmartMedi Dashboard </h1>
        <fieldset>  
        
          <legend><span class="number">1</span> Add Reminders</legend>
          
         <label for="name">Medication name:</label>
         <input required type="text" id="name" name="name">
        
         <label for="time">(Start)Time:</label>
         <input required type="text" id="time" name="time">
        
         <label for="interval">Interval:</label>
          <select required id="interval" name="interval">
            <optgroup label="Interval">
              <option value="HOURLY">Hourly</option>
              <option value="DAILY">Daily</option>
          </select>
          
         </fieldset>
       
        <button type="submit">Add reminder</button>
        
       </form>
        </div>
      </div>
      
    </body>
</html>
<!-- partial -->
  
</body>
</html>

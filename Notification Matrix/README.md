# Notification Matrix Manual

This manual describes the process of setting up the notification system for your smart medicine cabinet using an 8x8 WS2812 LED matrix for visual reminders.

## Recuirements

- ESP32 microcontroller
- 8x8 WS2812 LED matrix (for visual notifications)
- Cables female/female, and breadboard
- Web hosting server (for the website) 
- Database (MySQL/MariaDB) here's a getting started of MySQL that helps with the setup of MySQL: https://dev.mysql.com/doc/mysql-getting-started/en/

## External references

For more detailed steps on setting up a database and hosting a website, please refer to these manuals:

How To Install Apache on Ubuntu (DigitalOcean): https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04
How To Use FileZilla (FileZilla Documentation): https://wiki.filezilla-project.org/Documentation

## Step 1: Setting up the ESP32 and PlatformIO for the coding and upload

**Set up a New Project**

- Click on **+ New Project** in PlatformIO
<img width="1272" alt="Screenshot 2024-10-03 at 11 23 44" src="https://github.com/user-attachments/assets/12b5b11b-4910-489f-ac8b-dd324f0749a0">

- Select the esp32 dev module as board
- Click on **Finish** to setup the project

**Connect the WS2812 LED Matrix:**

- Connect the VCC pin of the matrix to 3.3V on the ESP32, GND to GND, and data-in pin (DIN) to GPIO 4.
- Use the FastLED library to control the WS2812 LED matrix and display notification icons.
- Check the code comments for the description of the code
> **Common Issue:** If the matrix does not display, ensure that the FastLED library is installed in de lib_deps and the correct GPIO pin is used.

**Connect to WIFI:**

- Connect the ESP32 to your Wi-Fi network using the WiFi library.
```
const char* ssid = "your_ssid";
const char* password = "your_password";

void connectToWiFi() {
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println(" Connected to Wi-Fi");
}
```
> **Checkpoint:** Print the Wi-Fi connection status to the Serial Monitor to ensure the ESP32 connects successfully. You should see the "Connected to Wi-Fi" message.

## Step 2: Connecting to the Webserver, Database and API

**Set up the database**
- Use PHP and MySQL to store reminders. The following snippet connects to the database:

```
  <?php
$host = 'localhost';
$db = 'db_name';
$user = 'db_user';
$pass = 'User_pass';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

> **Checkpoint:** After running this PHP code on the server, try accessing the PHP script through the browser to confirm a successful connection to the database.  
> **Common Issue**: Check if the database cridentials are correct and if the server is running

**API for retrieving the reminders**
- Create an API endpoint to fetch reminders from the database.
```
<?php
include 'php/db.php';

$sql = "SELECT id, `interval`, frequency, name FROM reminders";
$result = $conn->query($sql);

$reminders = [];
while ($row = $result->fetch_assoc()) {
    $reminders[] = $row;
}
echo json_encode($reminders);
$conn->close();
?>
```
> **Checkpoint:** Access this API endpoint from the browser and ensure it returns the reminders in JSON format.

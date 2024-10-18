# Notification Matrix Manual

This manual describes the process of setting up the notification system for your smart medicine cabinet using an 8x8 WS2812 LED matrix for visual reminders. I'll only use snippets of code, for the full code and the comments check the code in this folder for the esp32 code (`main.cpp`) and the Back-end in the Backend API + UI folder

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

![IMG_3597](https://github.com/user-attachments/assets/55142d51-ac1e-4f65-8e90-741038316b48)

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
> **Common Issue:** If the API does not return results, verify that the database query is correct and the table contains data.  

## Step 3: Fetching the Reminders on the ESP32

** Fetching Data from the API: **

- Use the HTTPClient and ArduinoJson libraries to fetch reminders from the API and parse the JSON response.

```
void fetchReminders() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin("https://iot.mellink.me/api_reminders.php");
    int httpCode = http.GET();

    if (httpCode == 200) {
      String payload = http.getString();
      DynamicJsonDocument doc(1024);
      deserializeJson(doc, payload);
      // Parse and store reminders
    }
    http.end();
  }
}
```

> **Checkpoint:** Print the API response to the Serial Monitor to ensure the ESP32 can successfully fetch and parse the reminders.  
> **Common Issue:** If the API call fails, check the server URL and/or ensure the device is connected to Wi-Fi.

** Displaying the notification: **

- Based on the reminders fetched, display a visual reminder using the LED matrix.
- This kind of matrix doen't have a driver so it is seen as a long LED-strip therefore this code to let a red cross blink:
```
void displayExclamationMark() {
  // Clear all LEDs first
  for (int i = 0; i < NUM_LEDS; i++) {
    leds[i] = CRGB::Black;
  }

  // Define the square box
  leds[0] = CRGB::DarkCyan;
  leds[1] = CRGB::Cyan;
  leds[2] = CRGB::Cyan;
  leds[3] = CRGB::Cyan;
  leds[4] = CRGB::Cyan;
  leds[5] = CRGB::Cyan;
  leds[6] = CRGB::Cyan;
  leds[7] = CRGB::DarkCyan;
  leds[8] = CRGB::Cyan;
  leds[9] = CRGB::Cyan;
  leds[10] = CRGB::Cyan;
  leds[13] = CRGB::Cyan;
  leds[14] = CRGB::Cyan;
  leds[15] = CRGB::Cyan;
  leds[16] = CRGB::Cyan;
  leds[17] = CRGB::Cyan;
  leds[18] = CRGB::Cyan;
  leds[21] = CRGB::Cyan;
  leds[22] = CRGB::Cyan;
  leds[23] = CRGB::Cyan;
  leds[24] = CRGB::Cyan;
  leds[31] = CRGB::Cyan;
  leds[32] = CRGB::Cyan;
  leds[39] = CRGB::Cyan;
  leds[40] = CRGB::Cyan;
  leds[41] = CRGB::Cyan;
  leds[42] = CRGB::Cyan;
  leds[45] = CRGB::Cyan;
  leds[46] = CRGB::Cyan;
  leds[47] = CRGB::Cyan;
  leds[48] = CRGB::Cyan;
  leds[49] = CRGB::Cyan;
  leds[50] = CRGB::Cyan;
  leds[53] = CRGB::Cyan;
  leds[54] = CRGB::Cyan;
  leds[55] = CRGB::Cyan;
  leds[56] = CRGB::DarkCyan;
  leds[57] = CRGB::Cyan;
  leds[58] = CRGB::Cyan;
  leds[59] = CRGB::Cyan;
  leds[60] = CRGB::Cyan;
  leds[61] = CRGB::Cyan;
  leds[62] = CRGB::Cyan;
  leds[63] = CRGB::DarkCyan;

  // Define the medic icon
  leds[11] = CRGB::Red;
  leds[12] = CRGB::Red;
  leds[19] = CRGB::Red;
  leds[20] = CRGB::Red;
  leds[25] = CRGB::Red;
  leds[26] = CRGB::Red;
  leds[27] = CRGB::Red;
  leds[28] = CRGB::Red;
  leds[29] = CRGB::Red;
  leds[30] = CRGB::Red;
  leds[33] = CRGB::Red;
  leds[34] = CRGB::Red;
  leds[35] = CRGB::Red;
  leds[36] = CRGB::Red;
  leds[37] = CRGB::Red;
  leds[38] = CRGB::Red;
  leds[43] = CRGB::Red;
  leds[44] = CRGB::Red;
  leds[51] = CRGB::Red;
  leds[52] = CRGB::Red;
}
```
> **Common Issue:** If the LEDs don't blink, verify that the LED array is correctly initialized and the display function is called at the right moment. Also check if the right pins are connected as described in step 1

## Step 4: Website interface for adding the reminders

**Create a form to add reminders on POST**
- Build a web form where users can input medication name, interval, and frequency.

```
<form action="add_reminder.php" method="post">
  <label for="name">Medication name:</label>
  <input type="text" id="name" name="name">
  
  <label for="time">Time:</label>
  <input type="text" id="time" name="time">
  
  <label for="interval">Interval:</label>
  <select id="interval" name="interval">
    <option value="HOURLY">Hourly</option>
    <option value="DAILY">Daily</option>
  </select>
  
  <button type="submit">Add reminder</button>
</form>
```

> **Common Issue:** If the form does not submit, check that the `action` attribute points to the correct PHP script.

**Handle form submission:**

- When the form is submitted, add the reminder to the database.

```
<?php
include 'php/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = htmlspecialchars($_POST['name']);
  $interval = htmlspecialchars($_POST['interval']);
  $time = htmlspecialchars($_POST['time']);

  $sql = "INSERT INTO reminders (name, `interval`, frequency) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $name, $interval, $time);
  $stmt->execute();
}
?>
```
> **Common Issue:** If the reminder does not get saved, verify the database schema matches the query.

### Troubleshooting:

- Double-check your wiring for the ESP32 and WS2812 LED matrix.
- Ensure database connection and server configuration are correct.
- Use tools like Postman to test API responses.



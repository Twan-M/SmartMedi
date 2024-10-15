#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <FastLED.h>
#include <ArduinoJson.h>

// WiFi credentials
const char* ssid = "xxxxx";
const char* password = "xxxxxxxxxxxx";

// API endpoint
const char* apiEndpoint = "https://iot.mellink.me/api_reminders.php";

// LED setup
#define LED_PIN     4
#define NUM_LEDS    64
#define BRIGHTNESS  64
#define LED_TYPE    WS2812B
#define COLOR_ORDER GRB

CRGB leds[NUM_LEDS];

// Reminder structure
struct Reminder {
  String interval;
  String frequency;
};

Reminder reminders[10];  // Array to store up to 10 reminders
int reminderCount = 0;

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

void clearGrid() {
  for (int i = 0; i < NUM_LEDS; i++) {
    leds[i] = CRGB::Black; // Turn off all LEDs
  }
}

void connectToWiFi() {
  Serial.print("Connecting to Wi-Fi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println(" Connected!");
}

void fetchReminders() {
  if ((WiFi.status() == WL_CONNECTED)) {
    HTTPClient http;
    http.begin(apiEndpoint);
    int httpCode = http.GET();

    if (httpCode == 200) { // Success
      String payload = http.getString();
      Serial.println("API Response: " + payload);

      // JSON document
      DynamicJsonDocument doc(1024);
      DeserializationError error = deserializeJson(doc, payload);

      if (!error) {
        reminderCount = 0;
        Serial.println("Fetched Reminders:");
        for (JsonObject reminder : doc.as<JsonArray>()) {
          if (reminderCount < 10) { // Limit to 10 reminders
            reminders[reminderCount].interval = reminder["interval"].as<String>();
            reminders[reminderCount].frequency = reminder["frequency"].as<String>();
            Serial.print("Reminder ");
            Serial.print(reminderCount + 1);
            Serial.print(": ");
            Serial.print("Interval: ");
            Serial.print(reminders[reminderCount].interval);
            Serial.print(", Time: ");
            Serial.println(reminders[reminderCount].frequency);
            reminderCount++;
          }
        }
      } else {
        Serial.println("Failed to parse JSON");
      }
    } else {
      Serial.print("Error code: ");
      Serial.println(httpCode);
    }
    http.end();
  }
}

void checkReminders() {
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("Failed to obtain time");
    return;
  }

  char currentTime[6];
  sprintf(currentTime, "%02d:%02d", timeinfo.tm_hour, timeinfo.tm_min);
  Serial.print("Current time: ");
  Serial.println(currentTime);

  for (int i = 0; i < reminderCount; i++) {
    if (reminders[i].interval == "DAILY" && reminders[i].frequency == currentTime) {
      Serial.print("Reminder matched! Blinking for reminder at ");
      Serial.println(reminders[i].frequency);
      
      long endTime = millis() + 60000; // 1 minute
      while (millis() < endTime) {
        displayExclamationMark();
        FastLED.show();
        delay(500);
        clearGrid();
        FastLED.show();
        delay(500);
      }
    }
  }
}

void setup() {
  Serial.begin(115200);
  FastLED.addLeds<LED_TYPE, LED_PIN, COLOR_ORDER>(leds, NUM_LEDS).setCorrection(TypicalLEDStrip);
  FastLED.setBrightness(BRIGHTNESS);

  connectToWiFi();

  // Set the time zone to Amsterdam
  configTime(3600, 3600, "pool.ntp.org", "time.nist.gov"); // 2 hours ahead of UTC
  delay(2000); // Wait for time sync

  fetchReminders(); // Initial fetch
}

void loop() {
  static unsigned long lastFetchTime = 0;
  unsigned long currentMillis = millis();

  // Refresh reminders from the API every 5 minutes
  if (currentMillis - lastFetchTime >= 300000) { // 300,000 ms = 5 minutes
    fetchReminders();
    lastFetchTime = currentMillis;
  }

  checkReminders(); // Check reminders every minute
  delay(60000); // Delay for 1 minute between checks
}

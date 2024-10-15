#include <WiFi.h>
#include <HTTPClient.h>
#include "HX711.h"

// Wifi-gegevens
const char* ssid = "xxxxx";
const char* password = "xxxxxxxxxxx";

// Load cell-instellingen
#define LOADCELL_DOUT_PIN 4
#define LOADCELL_SCK_PIN 5
HX711 scale;
long offset = 0;
float calibration_factor = 2107.4; // Kalibratiefactor voor nauwkeurigheid

// Medicatie-instellingen
const String medication_name = "Allergie";
const float pill_weight = 1.7; // Gewicht per pil in grammen
const unsigned long interval = 30000; // Interval van 30 seconden
unsigned long previousMillis = 0;

void setup() {
  Serial.begin(115200);
  
  // Verbinden met WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Verbinden met WiFi...");
  }
  Serial.println("WiFi Verbonden!");

  // HX711 instellen
  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);
  Serial.println("Offset bepalen, zorg dat de load cell leeg is...");
  delay(3000);

  offset = 0;
  int numReadings = 100;
  for (int i = 0; i < numReadings; i++) {
    offset += scale.read();
    delay(50);
  }
  offset /= numReadings;

  Serial.print("Offset bepaald: ");
  Serial.println(offset);
}

void loop() {
  unsigned long currentMillis = millis();
  
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;
    
    // Gewicht lezen en aantal pillen berekenen
    long rawValue = 0;
    int numReadings = 10;
    for (int i = 0; i < numReadings; i++) {
      rawValue += scale.read();
      delay(50);
    }
    rawValue /= numReadings;
    rawValue -= offset;
    
    float totalWeight = rawValue / calibration_factor;
    int pillCount = round(totalWeight / pill_weight); // Aantal pillen berekenen en afronden

    Serial.print("Totaal Gewicht: ");
    Serial.print(totalWeight);
    Serial.print(" | Aantal Pillen: ");
    Serial.println(pillCount);

    // API-aanroep voorbereiden
    if(WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      String url = "https://iot.mellink.me/api_updatemedication.php?name=" + medication_name + "&supply=" + String(pillCount);
      http.begin(url);
      int httpResponseCode = http.GET();
      
      if(httpResponseCode > 0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
      } else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      http.end();
    } else {
      Serial.println("Niet verbonden met WiFi");
    }
  }
}

#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <Adafruit_GFX.h>
#include <Adafruit_ST7789.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <TimeLib.h>

#define LCD_MOSI 23
#define LCD_SCLK 18
#define LCD_CS 15
#define LCD_DC 2
#define LCD_RST 4
#define LCD_BLK 32
Adafruit_ST7789 lcd = Adafruit_ST7789(LCD_CS, LCD_DC, LCD_RST);

// WiFi gegevens
const char* ssid = "xxxxx";
const char* password = "xxxxxxxxxxxxxxx";
const char* weather_api_url = "https://iot.mellink.me/api_weather.php?city=Amsterdam";
const char* medication_api_url = "https://iot.mellink.me/api_medication.php";

// NTP Client setup
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 2 * 3600, 60000); // Update elke 60 seconden

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  lcd.init(170, 320);
  lcd.setRotation(1);
  lcd.fillScreen(ST77XX_BLACK);
  lcd.setTextSize(2);
  lcd.setTextColor(ST77XX_WHITE);

  // Verbinding maken met WiFi
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Verbinding maken met WiFi...");
  }
  Serial.println("WiFi verbonden!");

  // Start de NTP client
  timeClient.begin();
}

void drawPillIcon(int x, int y) {
  lcd.fillCircle(x, y + 8, 10, ST77XX_WHITE);           // Witte cirkel voor het pil-icoon
  uint16_t grayColor = lcd.color565(169, 169, 169);      // Grijze kleur met RGB-waarden voor lichtgrijs
  lcd.drawLine(x - 10, y + 8, x + 10, y + 8, grayColor); // Grijze lijn door het midden
}


void loop() {
  float outsideTemp = 0.0;
  String medicationInfo = "";

  // Huidige tijd ophalen
  timeClient.update();
  unsigned long epochTime = timeClient.getEpochTime();
  setTime(epochTime);

  // Datum en tijd formatteren
  String currentDate = String(day()) + "/" + String(month()) + "/" + String(year());
  String currentTime = String(hour()) + ":" + (minute() < 10 ? "0" : "") + String(minute());

  StaticJsonDocument<1024> docMed;

  if (WiFi.status() == WL_CONNECTED) {
    // Temperatuur ophalen
    HTTPClient http;
    http.begin(weather_api_url);
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      String payload = http.getString();
      Serial.println(payload);

      // JSON-parsen
      StaticJsonDocument<2048> doc;
      DeserializationError weatherError = deserializeJson(doc, payload);

      if (!weatherError) {
        // Tijd van de ESP32 (UTC) vergelijken met de beschikbare data
        JsonArray weatherData = doc.as<JsonArray>();
        unsigned long closestTimeDiff = ULONG_MAX;

        for (JsonObject dataPoint : weatherData) {
          const char* dateStr = dataPoint["date"];
          struct tm tm;
          strptime(dateStr, "%Y-%m-%dT%H:%M:%SZ", &tm);
          time_t dataTime = mktime(&tm);

          unsigned long timeDiff = (epochTime > dataTime) ? epochTime - dataTime : dataTime - epochTime;
          if (timeDiff < closestTimeDiff) {
            closestTimeDiff = timeDiff;
            outsideTemp = dataPoint["temp"].as<float>();
          }
        }
      } else {
        Serial.println("Fout bij het parsen van JSON");
      }
    } else {
      Serial.print("Fout bij HTTP-aanvraag, code: ");
      Serial.println(httpResponseCode);
    }
    http.end();

    // Medicatiegegevens ophalen
    http.begin(medication_api_url);
    httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      String payload = http.getString();
      Serial.println(payload);

      // JSON-parsen voor medicatie
      DeserializationError medError = deserializeJson(docMed, payload);

      if (!medError) {
        int numPills = docMed.size();
        for (int i = 0; i < numPills; i++) {
          const char* name = docMed[i]["name"];
          int supply = docMed[i]["supply"];
          int max = docMed[i]["max"];
          medicationInfo += String(name) + " (" + String(supply) + "/" + String(max) + ")\n";
        }
      } else {
        Serial.println("Fout bij het parsen van JSON voor medicatie");
      }
    } else {
      Serial.print("Fout bij HTTP-aanvraag voor medicatie, code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }

  // Scherm vernieuwen
  lcd.fillScreen(ST77XX_BLACK);
  
  // Datum links weergeven
  lcd.setTextSize(3);
  lcd.setCursor(10, 10);
  lcd.print(currentDate);

  // Tijd en temperatuur op één regel met verticale lijn ertussen, links uitgelijnd
  lcd.setTextSize(2);
  lcd.setCursor(10, 50);
  lcd.print(currentTime);
  lcd.drawLine(80, 50, 80, 70, ST77XX_WHITE);
  lcd.setCursor(90, 50);
  lcd.print(outsideTemp);
  lcd.print(" C");

  // Horizontale lijn over de volledige breedte
  lcd.drawLine(0, 90, 170, 90, ST77XX_WHITE);

  // Medicatiegegevens weergeven onder de lijn
  int yPosition = 100;
  for (int i = 0; i < docMed.size(); i++) {
    drawPillIcon(10, yPosition);
    lcd.setCursor(30, yPosition);
    const char* name = docMed[i]["name"];
    int supply = docMed[i]["supply"];
    int max = docMed[i]["max"];
    int drawer = docMed[i]["drawer"];
    lcd.print(String(name) + " (" + String(supply) + "/" + String(max) + ")" + " | Nr " + String(drawer));
    yPosition += 25;  // Verplaats naar beneden voor de volgende pil
  }

  delay(30000);  // Update elke 30 seconden
}

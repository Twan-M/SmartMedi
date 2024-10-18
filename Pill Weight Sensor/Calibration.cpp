#include "HX711.h"

#define LOADCELL_DOUT_PIN 4
#define LOADCELL_SCK_PIN 5

HX711 scale;
long offset = 0;
float calibration_factor = 2107.4; // Change this value to calibrate your Load Cell

void setup() {
  Serial.begin(115200);
  
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
  long rawValue = 0;
  int numReadings = 10;
  
  for (int i = 0; i < numReadings; i++) {
    rawValue += scale.read();
    delay(50);
  }
  rawValue /= numReadings;

  rawValue -= offset;

  float weight = rawValue / calibration_factor;

  Serial.print("Gecorrigeerde waarde: ");
  Serial.print(rawValue);
  Serial.print(" | Gewicht: ");
  Serial.println(weight);

  delay(500);
}

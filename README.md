# SmartMedi
Automated pill management system. Includes API Backend; User Frontend; LCD Screen with information about weather, and pill supply; Notification matrix with automated alerts from API; Pill weight setup sensor.
## Backend API + User Interface
PHP Back-end, with HTML & CSS front-end. Shows information about current pill supply. Also provides API used to interact with the other ESP projects.
Allows users to add notifications, medications and manage the supply without using the Pill Weight project.
## Box LCD Screen
C++ Project for ESP32; fethces data from API to show current pill supply and weather on LCD Screen.
## Notification Matrix
C++ Project for ESP32; fetches data from API to blink on specific notification times. Notifications can be set-up using the Front-end.
## Pill Weight Sensor
C++ Project for ESP32; Weights amount of pills, based on setting set in the C++ code. Updates general supply using Back-End.

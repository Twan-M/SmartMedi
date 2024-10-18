# Pill Weight Sensor
This project allows you to measure pills in a box using an ESP32, a Load Cell and a PHP Backend. 
Combined this project will count pills placed in a box, and updats the web-interface with the correct amount of pills.

## Hardware & Software
1. EPS32
2. Visual Studio Code
3. Load Cell 1kg
4. WiFi Connection
5. API Backend + User interface (https://github.com/Twan-M/SmartMedi/tree/main/Backend%20API%20%2B%20User%20Interface)
6. Scale (Preferably accurate to 0.1g or 0.01g)
7. Load cell housing + Mount. You could use a generic mount or use the included 3MF file. (https://github.com/Twan-M/SmartMedi/tree/main/Pill%20Weight%20Sensor)

## Part 0: Installing the Hardware
1. Connect your ESP32 to your PC using the USB-C Port.
2. Connect your load cell  to the ESP using the following diagram:
![image](https://github.com/user-attachments/assets/d2c917f5-343f-4880-8149-d2c1c5cf5da9)
3. Add your Load Cell to the Load Cell Mount.
![image](https://github.com/user-attachments/assets/f462be49-6e95-46e3-95ef-217db131338e)
(From top  to bottom; Weight Plate -> Spacer -> Load Cell -> Bottom feet

## Part 1: Installing the Software
1. Install VScode "https://code.visualstudio.com/"
2. Click on the "Extensions Icon" in the left menu bar.
![Screenshot 2024-10-02 194856](https://github.com/user-attachments/assets/cefc23d5-3452-4084-9c74-2dcd56a496dd)
3. Search for the extension "PlatformIO IDE", and click on install. 
![Screenshot 2024-10-02 194947](https://github.com/user-attachments/assets/0219dffb-c65a-4104-aad5-027b6444f16c)
4. Click on the PlatformIO icon in the left menu bar.
5. You'll be navigated to the PlatformIO Homepage (If this doesnt open the page you can click on "PIO Home" and click on open to navigate to the same homepage.)
![Screenshot 2024-10-02 195428](https://github.com/user-attachments/assets/c7b199de-6870-40a6-a328-816ee9326899)
6. Click the button "New Project".
![image](https://github.com/user-attachments/assets/d08b246e-c371-48a9-b77b-9d6330414c0c)
7. Name your project.
8. Select your ESP Device (In our case it's the; Espressif ESP32 Dev Module)
9. Click on "Finish" to create your project.
![image](https://github.com/user-attachments/assets/a5c1d99c-6e9f-4247-a1c0-84bc7efee2db)

##Part 2: Calibrating the Load Cell
1. Copy the code in the file named: "Calibration.cpp" to start calibrating your Load Cell
2. Click the PlatformIO icon to navigate to the Homepage once again.
3. Click the Liberaries icon in the PlatformIO menu.
4. Search for "HX711"
![image](https://github.com/user-attachments/assets/fe92b7a8-ff18-41f1-a03d-dcbebe057261)
5. Click on the Blue button: "Add to Project", and select the project you just created.
6. Flash your ESP32 with the calibration code by clicking on the Flash button, on the bottom of the screen.
![image](https://github.com/user-attachments/assets/9b45c175-6a3d-48b6-93d0-fd3c9a3a49f7)
7. Wait for the Flash to finish, and click the Serial Monitor button. (Located in the same bar as the Flash button. It looks like a wallplug icon.) (Check the troubleshooting section below if your ESP is not connecting or doesnt flash)
8. Weigh a random item (Less than 1kg) using your scale. And write it down somewhere so you'll remember it.
9. Once the Serial monitor shows: "Offset: xxxx" you can place your item on the Load cell.
10. Look how close the reading is to the actual weight of your item. (If this is fluctuating a lot, check the troubleshooting steps)
11. If it matches, that's great! You're done with this step. If not use the following formula to get the correct calibration factor:
![image](https://github.com/user-attachments/assets/90f35e70-b1c7-4b1a-afb3-737e7357af2f)
12. Enter the new calibration factor on the top of the code:
![image](https://github.com/user-attachments/assets/cc0adb93-bf7d-43d6-b9e3-aa67a3af29fd)
13. Repeat those steps, until the measured value is the same as your known item weight. (A difference of 0.1-0.3 gram is fine)
14. You now have the correct calibration factor for your Load Cell. Write this down somewhere aswell, you'll need this factor later.

##Part 3: The project code
1. Copy the code of the file named: "main.cpp" instead of the current calibration code.
2. Replace the WiFi connection details with the details of your own WiFi network or Hotspot. (If the ESP is not connecting to WiFi, visit the troubleshooting steps below)
![image](https://github.com/user-attachments/assets/6a8c379b-4d7e-4ea6-8f2e-5e158b8015d1)
3. Replace the API link to the API link of your own Back-end. You only have the replace the domain part (https://iot.mellink.me/). As your own endpoint will have the same URL parameters if set-up correctly.
![image](https://github.com/user-attachments/assets/dbc6ebaa-2cea-4a44-be23-9542d0b70c17)
4. Replace the calibration facor, with the value you just written down.
![image](https://github.com/user-attachments/assets/33b6c9c5-5d7e-494e-895d-19521979ec2a)
5. Replace the Pill values with the values appropiate for your usecase. medication_name is the name you've given your medication in the web-app. pill_weight is the weight of a single pill. (Use the scale once again to determine this).
![image](https://github.com/user-attachments/assets/821cb3a8-3013-4374-9dc8-292307b84d6a)
6. Flash the current code to your ESP using the same steps as previous.
7. Open the serial monitor once again, it'll show you instructions. Place a pill on the Load Cell once the offset has been given.
8. The Serial monitor will show you how many pills are on the Load Cell. (If not check the troubleshooting steps below)

##Part 4: Troubleshooting
1. Is flashing your ESP giving an error? Make sure the correct COM port is selected.
![image](https://github.com/user-attachments/assets/0a40029c-f7c8-4406-a0b7-55e9b7f08a55)
2. Is the Load cell giving readingings back which are fluctuating a lot, make sure the load cell is connected the same as the diagram, and make sure your solder points are good. 
3. Are you nog immidiatly getting a update on the amount of pills? Thats correct, it currently only updates once every 30 seconds. This can be changed using the following variable:
![image](https://github.com/user-attachments/assets/c72f4d58-b550-4e8d-96d0-505676ffe438)
4. Does your computer not recognize your ESP? Try downloading the drivers manually: "https://docs.espressif.com/projects/esp-idf/en/stable/esp32/get-started/establish-serial-connection.html"
5. Is your ESP not updating the user web interface? Make sure your ESP is connected to the WiFi.
6. Is your ESP not connecting to WiFi? Make sure your WiFi has a 2.4GHz band, or make sure you enable "Maximise Compatibiliy" for your Hotspot





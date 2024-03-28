#include <SoftwareSerial.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <Wire.h> //for I2C devices
#include <Adafruit_ADS1X15.h>  //ADS1115 library


//HC12 pins connection------------------------
//10 --> HC12 Tx
//11 --> HC12 Rx
//5V --> VCC
//Gnd --> Gnd
//------------------------

//=============================================================================
    // Command 1: water all plots
    // command 2: water specific plot
    // Command 3: station request data from field
    // command 4: sensors detect low moisture level
    // command 5: update field microcontroller eeprom data
//=============================================================================

char plot1 = 'A';
char plot2 = 'B';
char formattedData[20];
uint16_t avg_moisture;

const byte numChars = 32; //array size
const byte addr_length = 10; //mcu id : "fmcu1"
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data (EXAMPLE)
char messageFromPC[numChars] = {0};
int integerFromPC = 0;
float floatFromPC = 0.0;

//variable for parsed data (LATEST)
int command = 0;
char SA[addr_length];
char DA[addr_length];
char payloadFromESP;

boolean newData = false;

int BAUD_RATE = 9600;
SoftwareSerial HC12(10, 11); //RX(connect to module Tx),TX(Connect to module Rx)



byte incomingByte;
String readBuffer = "";
String receivedData = "";

//Read moisture sensor variable
Adafruit_ADS1115 ads1; //ADS1115 ADDR DEFAULT OR PIN CONNECT TO GND (0x48)
Adafruit_ADS1115 ads2; //ADS1115 ADDR PIN CONNECT TO VDD (0x49)
const float multiplier = 0.1875F;


#include "field_function.h" //adress after define variables

void setup() {
  Serial.begin(BAUD_RATE);             // Serial port to computer
  HC12.begin(BAUD_RATE);               // Serial port to HC12
  ads1.begin(0x48);
  ads2.begin(0X49);

}


void loop() {

  //test data format: <HelloWorld, 12, 24.7>
  //test data format: <3, smcu1, fmcu1, 5>
  recvWithStartEndMarkers();
  if (newData == true) {
        strcpy(tempChars, receivedChars);
            // this temporary copy is necessary to protect the original data
            //   because strtok() used in parseData() replaces the commas with \0
        parseData();
        showParsedData();
        newData = false;
    }

  delay(100);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }


  switch (command)
  {
    case 3:
    //check destination..if wrong, ignore
    int DA_check = check_destination_address();
    int SA_check = check_source_address();
    if(!DA_check)
    {
      //ignore
      break;
    }

    //check source.. make sure there's endpoint to send the moisture reading(not go missing)
    if(!SA_check)
    {
      Serial.println("Source Address empty!");
      break;
    }
    
    //read moisture sensor.. get the average
    avg_moisture = read_soil_moisture(&payload);
    
    //pack data into format <C,SA,DA,P>...
    formatData(char* formattedData, &C, &SA, &DA, &avg_moisture);
    //send data via HC12
    break;

    default:
    break;
  }
}

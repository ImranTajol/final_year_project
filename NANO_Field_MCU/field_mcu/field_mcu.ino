#include <SoftwareSerial.h> //make GPIO pins as UART pin
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <Wire.h> //for I2C devices
#include <Adafruit_ADS1X15.h>  //ADS1115 library
#include <ctype.h>
#include <EEPROM.h>

#define mcu_id "fmcu1"
#define ADDR_LENGTH 10
#define PLOT_1 "A"
#define PLOT_2 "B"


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
//    <3, smcu1, fmcu1, A>
//=============================================================================

//=============================================================================
//    ADS1115 addr => 0x48(GND), 0x49(VDD), 0x4A(SDA), 0x4B(SCL)
//=============================================================================

char formattedData[20];
uint16_t avg_moisture;

const byte numChars = 32; //array size
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data (EXAMPLE)
char messageFromPC[numChars] = {0};
int integerFromPC = 0;
float floatFromPC = 0.0;

//variable for parsed data (LATEST)
uint8_t command = 0;
char SA[ADDR_LENGTH];
char DA[ADDR_LENGTH];
char payloadFromESP[2];

boolean newData = false;
boolean doneTransmit = false; // to check current data already transmit

int BAUD_RATE = 9600;
SoftwareSerial HC12(10, 11); //RX(connect to module Tx),TX(Connect to module Rx)



byte incomingByte;
String readBuffer = "";
String receivedData = "";

//Read moisture sensor variable
Adafruit_ADS1115 ads1; //ADS1115 ADDR DEFAULT OR PIN CONNECT TO GND (0x48)
Adafruit_ADS1115 ads2; //ADS1115 ADDR PIN CONNECT TO VDD (0x49)
const float multiplier = 0.1875F;


int address_plot1 = 0;
int address_plot2 = 0;
int val_plot1 = 0;
int val_plot2 = 0;

#include "field_function.h" //adress after define variables

void setup() {
  Serial.begin(BAUD_RATE);             // Serial port to computer
  HC12.begin(BAUD_RATE);               // Serial port to HC12
  ads1.begin(0x48);
  ads2.begin(0X49);

}


void loop() {

  //test data format: <HelloWorld, 12, 24.7>
  //test data format: <3, smcu1, fmcu1, A>

  newData = false;
  doneTransmit = false;
  
  recvWithStartEndMarkers(); 
  
  if (newData == true) {
        
        strcpy(tempChars, receivedChars);
        // this temporary copy is necessary to protect the original data
        // because strtok() used in parseData() replaces the commas with \0
        
        // <3, smcu1, fmcu1, A> --> <3,smcu1,fmcu1,A> (no spaces)
        remove_spaces(tempChars,tempChars);
        parseData();
        showParsedData();
//        newData = false;
        
    }

  delay(200);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }


  //if have new data, only then trigger switch case.. newData = false after switch case done
  if(newData == true && doneTransmit == false)
  {
    
    switch (command)
    { 
      case 3:
      {
        Serial.println("Command is 3");

        //check destination..if wrong, ignore
        int DA_check = check_destination_address();
        int SA_check = check_source_address();
        if(!DA_check)
        {
          Serial.println("Address do not match!");
          //ignore
          break;
        }
    
        //check source.. make sure there's endpoint to send the moisture reading(not go missing)
        if(!SA_check)
        {
          Serial.println("Source Address empty!");
          break;
        }

        

        if(strcmp(payloadFromESP,PLOT_1) != 0 && strcmp(payloadFromESP,PLOT_2) != 0) //only ascii A and B
        {
          Serial.println("Assigned plot to mcu are A(65) & B(66)!");
          break;
        }
    
        
        //read moisture sensor.. get the average
        avg_moisture = read_soil_moisture(payloadFromESP);
        delay(100);

      
        //pack data into format <C,SA,DA,P>...
        formatData(formattedData, &command, SA, DA, payloadFromESP, &avg_moisture);
        
        //send data via HC12
        txData_HC12(formattedData);
        doneTransmit = true;
        break;
  
      }//end case 3

      
      default:
      Serial.println("Command besides 3 received");
      break;
    }
  
  }//end if(newData == true && doneTransmit == false)

  
}//end void loop

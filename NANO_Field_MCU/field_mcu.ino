#include <SoftwareSerial.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>


const byte numChars = 32; //array size
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data
char messageFromPC[numChars] = {0};
int integerFromPC = 0;
float floatFromPC = 0.0;

boolean newData = false;

int BAUD_RATE = 9600;
SoftwareSerial HC12(2, 3); //RX(connect to module Tx),TX(Connect to module Rx)

byte incomingByte;
String readBuffer = "";
String receivedData = "";

void setup() {
  Serial.begin(BAUD_RATE);             // Serial port to computer
  HC12.begin(BAUD_RATE);               // Serial port to HC12

}


void loop() {

  //test data format: <HelloWorld, 12, 24.7>
  recvWithStartEndMarkers();
  if (newData == true) {
        strcpy(tempChars, receivedChars);
            // this temporary copy is necessary to protect the original data
            //   because strtok() used in parseData() replaces the commas with \0
        parseData();
        showParsedData();
        newData = false;
    }

    
//  // ==== Storing the incoming data into a String variable
//  while (HC12.available()) {             // If HC-12 has data
//    incomingByte = HC12.read();          // Store each icoming byte from HC-12
//    readBuffer += char(incomingByte);    // Add each byte to ReadBuffer string variable
//    //Serial.write(incomingByte);
//    Serial.println(readBuffer);
//  }
  

  delay(100);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }
}


//receive data from another HC12
void recvWithStartEndMarkers() {
    static boolean recvInProgress = false;
    static byte ndx = 0;
    char startMarker = '<';
    char endMarker = '>';
    char rc;

    while (HC12.available() > 0 && newData == false) {
        rc = HC12.read();

        if (recvInProgress == true) {
            if (rc != endMarker) {
                receivedChars[ndx] = rc;
                ndx++;
                if (ndx >= numChars) {
                    ndx = numChars - 1;
                }
            }
            else {
                receivedChars[ndx] = '\0'; // terminate the string
                recvInProgress = false;
                ndx = 0;
                newData = true;
            }
        }

        else if (rc == startMarker) {
            recvInProgress = true;
        }
    }
}

void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    strcpy(messageFromPC, strtokIndx); // copy it to messageFromPC
 
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    integerFromPC = atoi(strtokIndx);     // convert this part to an integer

    strtokIndx = strtok(NULL, ",");
    floatFromPC = atof(strtokIndx);     // convert this part to a float

}

void showParsedData() {
    Serial.print("Message ");
    Serial.println(messageFromPC);
    Serial.print("Integer ");
    Serial.println(integerFromPC);
    Serial.print("Float ");
    Serial.println(floatFromPC);
}

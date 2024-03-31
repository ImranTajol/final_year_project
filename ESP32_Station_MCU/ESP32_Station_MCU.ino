#include <WiFi.h>
#include <HTTPClient.h>
//#include <AsyncTCP.h>
//#include <ESPAsyncWebServer.h>
#include <ArduinoJson.h>
#include <ArduinoWebsockets.h>
#include <SoftwareSerial.h>
#include "station_function.h"


//------------------------
//5 --> HC12 Tx
//18 --> HC12 Rx
//3.3V --> VCC
//Gnd --> Gnd
//------------------------

//=============================================================================
    // Command 1: water all plots
    // command 2: water specific plot
    // Command 3: station request data from field
    // command 4: sensors detect low moisture level
    // command 5: update field microcontroller eeprom data
//=============================================================================

#define RX 5
#define TX 18

#define mcu_id "smcu1"
#define ADDR_LENGTH 10

using namespace websockets;

//variable for parsed data (LATEST) HC12
uint8_t command = 0;
char SA[ADDR_LENGTH];
char DA[ADDR_LENGTH];
int payloadFromNANO;

boolean newData = false;

int BAUD_RATE = 9600;
EspSoftwareSerial::UART HC12;

//for incoming data using HC12
byte incomingByte;
String readBuffer = "";
String receivedData = "";


WebsocketsClient socket;
const char* websocketServer = "ws://192.168.43.7:81/";
boolean connected = false;

const char* ssid = "TKRIB_2.4G";
const char* password = "kamsiah062011";

struct parsedFromWS{
  int command;
  char source_addr[ADDR_LENGTH];
  char destination_addr[ADDR_LENGTH];
  int payload;
};


//=============================================================SETUP AND LOOP==========================================================================

void setup() {
  Serial.begin(9600);

  parsedFromWS myData;

  //setup HC12 UART
  HC12.begin(BAUD_RATE, EspSoftwareSerial::SWSERIAL_8N1, RX, TX);
  
  connect_Wifi(); // function has while loop to ensure connection is established
  connect_webSocket(websocketServer);
  socket.onMessage(handleMessage);
  socket.onEvent(handleEvent);
  
}

void loop() {
  if(!connected)
  {
    Serial.println("Connecting to WebSocket server");
    //retry connection until sucessful and update the "connected" boolean
    connect_webSocket(websocketServer);
  }

  socket.poll(); //transfer control to appropriate function to handle event message

  recvWithStartEndMarkers();
  if (newData == true) {
        strcpy(tempChars, receivedChars);
            // this temporary copy is necessary to protect the original data
            //   because strtok() used in parseData() replaces the commas with \0
        parseData();
        showParsedData();
        newData = false;
    }

    delay(200);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }

}

//=======================================================================================================================================





//=======================================================WEBSOCKETS FUNCTIONS================================================================================

void handleMessage(WebsocketsMessage message)
{
  Serial.println(message.data());
  if(message.data()!= "Welcome to the server.")
  {
    //arg: ws message , struct
    parseJsonData(message.data(),&myData);
  }
  
}

void handleEvent(WebsocketsEvent event, WSInterfaceString data)
{
  
}

//=======================================================================================================================================




//================================================================INITIAL SETUP FUNCTIONS=======================================================================
void connect_Wifi()
{
  // Connect to Wi-Fi network with SSID and password
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  // Print local IP address and start web server
  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void connect_webSocket(const char* websocketServer)
{
  connected = socket.connect(websocketServer);
  if(connected)
  {
    Serial.println("Websocket server Connected");
  }
  else
  {
    Serial.println("Connection failed.");
  }
  
}

//=======================================================================================================================================

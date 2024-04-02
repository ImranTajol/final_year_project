#include <WiFi.h>
#include <HTTPClient.h>
//#include <AsyncTCP.h>
//#include <ESPAsyncWebServer.h>
#include <ArduinoJson.h>
#include <ArduinoWebsockets.h>
#include <SoftwareSerial.h>
//#include "station_function.h"


//------------------------
//5 --> HC12 Tx
//18 --> HC12 Rx
//3.3V --> VCC
//Gnd --> Gnd
//------------------------

//FUNCTION DEFINITION
void connect_Wifi();
void connect_webSocket(const char* );
//void handleMessage(WebsocketsMessage);
//void handleEvent(WebsocketsEvent, WSInterfaceString );

//=============================================================================
    // Command 1: water all plots
    // command 2: water specific plot
    // Command 3: station request data from field
    // command 4: sensors detect low moisture level
    // command 5: update field microcontroller eeprom data
//=============================================================================

#define RX 5
#define TX 18

#define MCU_ID "smcu1"
#define ADDR_LENGTH 10

using namespace websockets;

const byte numChars = 32; //array size
char receivedChars[numChars];
char tempChars[numChars]; 


boolean newData = false;
boolean doneExecuteOperation= false;

int BAUD_RATE = 9600;
EspSoftwareSerial::UART HC12;

//for incoming data using HC12(temporary)
byte incomingByte;
String readBuffer = "";
String receivedData = "";


WebsocketsClient socket;
const char* websocketServer = "ws://192.168.1.102:81/";
boolean connected = false;

const char* ssid = "TKRIB_2.4G";
const char* password = "kamsiah062011";


////variable for data from HC12 (using global variable)
//uint8_t command = 0;
//char SA[ADDR_LENGTH];
//char DA[ADDR_LENGTH];
//int payloadFromNANO;

//variable for data from ws and HC12
struct parsedData{
  int command;
  char source_addr[ADDR_LENGTH];
  char destination_addr[ADDR_LENGTH];
  int payload;
};

parsedData myData; //instantiate struct

//include user defined libary
#include "station_function.h"


//=============================================================SETUP AND LOOP==========================================================================

void setup() {
  Serial.begin(9600); // setup serial monitor
  HC12.begin(BAUD_RATE, EspSoftwareSerial::SWSERIAL_8N1, RX, TX);//setup HC12 UART

  
  
  connect_Wifi(); // function has while loop to ensure connection is established
  connect_webSocket(websocketServer);
  socket.onMessage(handleMessage);
  socket.onEvent(handleEvent);
  
}

void loop() {
  //connected bool is set when websocket successful connection
  if(!connected)
  {
    Serial.println("Connecting to WebSocket server");
    //retry connection until sucessful and update the "connected" boolean
    connect_webSocket(websocketServer);
  }

  socket.poll(); //transfer control to appropriate function to handle event message

  recvWithStartEndMarkers(); //handle if data receive through UART
  if (newData == true) {
    
        // copy is necessary to protect the original data
        // because strtok() used in parseDataFromHC12() replaces the commas with \0
        strcpy(tempChars, receivedChars);  
        parseDataFromHC12(&myData);
        showParsedData(myData); //display parsed data
        newData = false;
    }

  delay(200);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }
  delay(200);

  //check if there is new that is not executed using function..check destination is correct..ignore if wrong
  if(newData == true && doneExecuteOperation == false && myData.destination_addr == MCU_ID)
  {
    mcu_operation(myData); //pass struct to function
    doneExecuteOperation = true;
  }

}//end void loop

//=======================================================================================================================================





//=======================================================WEBSOCKETS FUNCTIONS================================================================================

void handleMessage(WebsocketsMessage message)
{
  Serial.println(message.data());
  if(message.data()!= "Welcome to the server.")
  {
    parseJsonData(message.data());
    display_struct(myData);
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

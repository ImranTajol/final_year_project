#include <WiFi.h>
#include <HTTPClient.h>
//#include <AsyncTCP.h>
//#include <ESPAsyncWebServer.h>
#include <ArduinoJson.h>
#include <ArduinoWebsockets.h>
#include <SoftwareSerial.h>
#include <map>
#include <vector>


#define PUMP1 22        
#define PUMP2 23
#define RELAY1 21 //gpio 13 of expansion board is faulty
#define RELAY2 12
#define RELAY3 14
#define RELAY4 27
#define RELAY5 26
#define RELAY6 25
#define RELAY7 33
#define RELAY8 32
#define webserver_status 15
#define wifi_status 4
#define MAXPLOT 3 //shld be 7 (0 to 7) for (ABCDEFGH plots)..2 for testing
#define acceptable_threshold_margin 1000

bool PUMP1_ON = false;
bool PUMP2_ON = false;
bool PUMP1_FIRST_ON = true;
bool PUMP2_FIRST_ON = true;
int pump1_prev = 0;
int pump2_prev = 0;
int pump1_on_duration = 0;
int pump2_on_duration = 0;

#define RX 5
#define TX 18

#define MCU_ID "smcu1"
#define ADDR_LENGTH 10

using namespace websockets;

WebsocketsClient socket;
const char* websocketServer = "ws://bk2011018-fyp-web-socket-server.glitch.me/";
boolean connected = false;

const char* ssid = "UltramanCosmos";
const char* password = "Tuhau123";

struct RelayInfo {
    int pump_pins;
    int relay_pins;
    int prev_time;
    int watering_duration;
    bool watering_status;
};

std::map<std::string, RelayInfo> watering_mechanism = {
        {"A", {PUMP1, RELAY1, 0, 7000, false}},
        {"B", {PUMP1, RELAY2, 0, 3000, false}},
        {"C", {PUMP2, RELAY3, 0, 5000, false}},
        {"D", {PUMP2, RELAY4, 0, 4000, false}},
        {"E", {PUMP1, RELAY5, 0, 3000, false}},
        {"F", {PUMP1, RELAY6, 0, 3000, false}},
        {"G", {PUMP2, RELAY7, 0, 3000, false}},
        {"H", {PUMP2, RELAY8, 0, 3000, false}}
    };

//------------------------
//5 --> HC12 Tx
//18 --> HC12 Rx
//3.3V --> VCC
//Gnd --> Gnd
//------------------------

//---------------------------------
//Pin association to relay and pump
//Pump 1 ------ GPIO 22
//Pump 2 ------ GPIO 23
//Relay 1 ------ GPIO 13
//Relay 2 ------ GPIO 12
//Relay 3 ------ GPIO 14
//Relay 4 ------ GPIO 27
//Relay 5 ------ GPIO 26
//Relay 6 ------ GPIO 25
//Relay 7 ------ GPIO 33
//Relay 8 ------ GPIO 32

//---------------------------------



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
//    <3, smcu1, fmcu1, 65>
//=============================================================================



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

const unsigned long request_moisture_interval = 20000; //in milliseconds
const unsigned long send_plots_command_interval = 2000;
unsigned long previousTime_req_moisture = 0;
unsigned long previousTime_plots_command = 0;
int iterate_command = 0;
bool req_moisture_data = true;


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
  char plot_id[2];
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

  pinMode(PUMP1,OUTPUT);
  pinMode(PUMP2,OUTPUT);
  pinMode(RELAY1,OUTPUT);
  pinMode(RELAY2,OUTPUT);
  pinMode(RELAY3,OUTPUT);
  pinMode(RELAY4,OUTPUT);
  pinMode(RELAY5,OUTPUT);
  pinMode(RELAY6,OUTPUT);
  pinMode(RELAY7,OUTPUT);
  pinMode(RELAY8,OUTPUT);
  pinMode(webserver_status,OUTPUT);
//  pinMode(wifi_status,OUTPUT);

}

void loop() {
  delay(10);
  //connected bool is set when websocket successful connection
  if(!connected)
  {
    Serial.println("Connecting to WebSocket server");
    //retry connection until sucessful and update the "connected" boolean
    connect_webSocket(websocketServer);
  }

  socket.poll(); //transfer control to appropriate function to handle event message

  unsigned long current_time_trigger_pump = millis();
  unsigned long currentTime_req_moisture = millis(); 

  //15 minutes gap to execute new moisture req
  if (connected && (currentTime_req_moisture - previousTime_req_moisture >= request_moisture_interval) && req_moisture_data == false) 
  {
    Serial.println("");
    Serial.println("-----------------");
    Serial.println("Next req moisture");
    req_moisture_data = true;
    previousTime_req_moisture = currentTime_req_moisture;
  }

  //2 seconds gap to iterate all plot req
  if (connected && (currentTime_req_moisture - previousTime_plots_command >= send_plots_command_interval) && req_moisture_data == true) 
  {
    reqData_HC12(&iterate_command);
    previousTime_plots_command = currentTime_req_moisture;
  }

//-------------- PUMP ------------------

  if((current_time_trigger_pump - pump1_prev >= pump1_on_duration) && (PUMP1_ON == true))
  {
    digitalWrite(PUMP1,LOW);
    PUMP1_ON = false;
    PUMP1_FIRST_ON = true;
  }

  if((current_time_trigger_pump - pump2_prev >= pump2_on_duration) && (PUMP2_ON == true))
  {
    digitalWrite(PUMP2,LOW);
    PUMP2_ON = false;
    PUMP2_FIRST_ON = true;
  }

//--------------------------------

  if((current_time_trigger_pump - watering_mechanism["A"].prev_time >= watering_mechanism["A"].watering_duration) && (watering_mechanism["A"].watering_status == true))
  {
    digitalWrite(watering_mechanism["A"].relay_pins,LOW);
    watering_mechanism["A"].watering_status = false;
  }

  if((current_time_trigger_pump - watering_mechanism["B"].prev_time >= watering_mechanism["B"].watering_duration) && (watering_mechanism["B"].watering_status == true))
  {
    digitalWrite(watering_mechanism["B"].relay_pins,LOW);
    watering_mechanism["B"].watering_status = false;
  }
  if((current_time_trigger_pump - watering_mechanism["C"].prev_time >= watering_mechanism["C"].watering_duration) && (watering_mechanism["C"].watering_status == true))
  {
    digitalWrite(watering_mechanism["C"].relay_pins,LOW);
    watering_mechanism["C"].watering_status = false;
  }
  if((current_time_trigger_pump - watering_mechanism["D"].prev_time >= watering_mechanism["D"].watering_duration) && (watering_mechanism["D"].watering_status == true))
  {
    digitalWrite(watering_mechanism["D"].relay_pins,LOW);
    watering_mechanism["D"].watering_status = false;
  }

  

 

  recvWithStartEndMarkers(); //handle if data receive through UART
  if (newData == true && doneExecuteOperation == false) {
    
        // copy is necessary to protect the original data
        // because strtok() used in parseDataFromHC12() replaces the commas with \0
        strcpy(tempChars, receivedChars);  
        parseDataFromHC12(&myData);
//        showParsedData(myData); //display parsed data

        mcu_operation(myData); //pass struct to function
        doneExecuteOperation = true;
    
        newData = false;
    }

  delay(200);
  // ==== Sending data from one HC-12 to another via the Serial Monitor
  while (Serial.available()) {
    HC12.write(Serial.read());
  }


}//end void loop

//=======================================================================================================================================





//=======================================================WEBSOCKETS FUNCTIONS================================================================================

void handleMessage(WebsocketsMessage message)
{
  Serial.println("");
  Serial.println(message.data());
  Serial.println("");
  if(message.data()!= "Welcome to the server.")
  {
    parseJsonData(message.data());
//    display_struct(myData);
    mcu_operation(myData); //pass struct to function
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
//  digitalWrite(wifi_status, HIGH);
  digitalWrite(webserver_status, HIGH);
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
//  digitalWrite(wifi_status, LOW);
}

void connect_webSocket(const char* websocketServer)
{
  connected = socket.connect(websocketServer);
  if(connected)
  {
    Serial.println("Websocket server Connected");
    delay(100);
    digitalWrite(webserver_status, LOW);
  }
  else
  {
//    digitalWrite(webserver_status, HIGH);
    Serial.println("Connection failed.");
  }
  
}

//=======================================================================================================================================

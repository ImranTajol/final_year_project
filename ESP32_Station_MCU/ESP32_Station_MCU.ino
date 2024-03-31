#include <WiFi.h>
#include <HTTPClient.h>
//#include <AsyncTCP.h>
//#include <ESPAsyncWebServer.h>
#include <ArduinoJson.h>
#include <ArduinoWebsockets.h>
#include <SoftwareSerial.h>


//------------------------
//5 --> HC12 Tx
//18 --> HC12 Rx
//3.3V --> VCC
//Gnd --> Gnd
//------------------------

#define RX 5
#define TX 18

#define mcu_id "smcu1"
#define ADDR_LENGTH 10

using namespace websockets;

//variable for parsed data (LATEST)
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

//FUNCTION DEFINITION
void connect_Wifi();
void connect_webSocket(const char* websocketServer);
void handleEvent(WebsocketsEvent event, WSInterfaceString data);
void handleMessage(WebsocketsMessage message);
void mcu_operation(int command);
void water_all();
void water_plot();

WebsocketsClient socket;
const char* websocketServer = "ws://192.168.43.7:81/";
boolean connected = false;

const char* ssid = "TKRIB_2.4G";
const char* password = "kamsiah062011";


//=============================================================SETUP AND LOOP==========================================================================

void setup() {
  Serial.begin(9600);

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

}

//=======================================================================================================================================





//=======================================================WEBSOCKETS FUNCTIONS================================================================================

void handleMessage(WebsocketsMessage message)
{
  Serial.println(message.data());
  if(message.data()!= "Welcome to the server.")
  {
    parseJsonData(message.data());
  }
  
}

void handleEvent(WebsocketsEvent event, WSInterfaceString data)
{
  
}

//=======================================================================================================================================

void parseJsonData(String data_from_ws)
{
  StaticJsonDocument<256> doc; //256 bytes buffer
  // Deserialize the JSON document
  DeserializationError error = deserializeJson(doc, data_from_ws);

  // Test if parsing succeeds.
  if (error) {
    Serial.print(F("deserializeJson() failed: "));
    Serial.print(data_from_ws);
    Serial.println(error.c_str());
    return;
  }

  int command = doc["C"];
  const char * sa = doc["SA"];
  const char * da = doc["DA"];
  int payload = doc["P"];


  Serial.print("JSON data received: ");
  Serial.println(data_from_ws);
  Serial.print("Command: ");
  Serial.println(command);
  Serial.print("Source Address: ");
  Serial.println(sa);
  Serial.print("Destination Address: ");
  Serial.println(da);
  Serial.print("Payload: ");
  Serial.println(payload);

  mcu_operation(command);

  //format between HC12
  // <command,SA,DA,payload>
  //ex: String HC12_data = 
  //    char formattedString[100];
  //    int num1 =4;
  //    int num2 = 6;
  //    char* payload = "jack";
  //    sprintf(formattedString, "<command,%d,%d,%s>", num1, num2, payload);
  //    printf("Formatted string: %s\n", formattedString);  
}


void mcu_operation(int command)
{
  switch (command)
  {
    case 1:
      water_all();
      break;

    case 2:
      water_plot();
      break;

    case 3:
      //station request data from field
      break;

    case 4:
      //sensors detect low moisture level
      break;

    case 5:
      //update field microcontroller eeprom data
      break;

    default:
      //undetermined command
      break;
  }
  
}

void water_all()
{
  Serial.println("");
  Serial.println("Watering all plots....");
  Serial.println("");
}

void water_plot()
{
  Serial.println("");
  Serial.println("Watering plot X....");
  Serial.println("");
}

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

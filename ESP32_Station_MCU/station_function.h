#ifndef _STATION_FUNCTION_H
#define _STATION_FUNCTION_H


//FUNCTION DEFINITION
void connect_Wifi();
void connect_webSocket(const char* websocketServer);
void handleEvent(WebsocketsEvent event, WSInterfaceString data);
void handleMessage(WebsocketsMessage message);
void mcu_operation(int command);
void water_all();
void water_plot();
void txData_HC12(char* s)

void parseJsonData(String data_from_ws, struct parsedFromWS *parsedData)
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

  parsedData.command = doc["C"];
  strcpy(parsedData.source_addr, doc["SA"]);
  strcpy(parsedData.destination_addr, doc["DA"]);
  parsedData.payload = doc["P"];
//  int command = doc["C"];
//  const char * sa = doc["SA"];
//  const char * da = doc["DA"];
//  int payload = doc["P"];


  mcu_operation(parsedData.command);

  
}

void mcu_operation(struct parsedFromWS myStruct)
{
  switch (myStruct.command)
  {
    case 1:
      water_all();
      break;

    case 2:
      water_plot();
      break;

    case 3:
      //station request data from field
      //format between HC12
      
  //ex: String HC12_data = <command,SA,DA,payload>
      char formattedString[50]; //temp string variable
      //payload from Ws (decimal) --> payload process by ESP (decimal) --> parsed at field (dec -> char using ascii)
      sprintf(formattedString, "<%d,%s,%s,%d>",myStruct.command, myStruct.source_addr, myStruct.destination_addr, myStruct.payload);
      txData_HC12(formattedString);  //transmit what content of string
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

void txData_HC12(char* s)
{
  //iterate each byte in string for transmission
  Serial.print("To send to ESP: ");
  Serial.println(s);
  for(int i=0; i < strlen(s); i++)
  {
    HC12.write(s[i]);
  }
  
}

void display_struct(struct parsedFromWS d)
{
  Serial.println("Parsed data from WS");
  Serial.print("Command: ");
  Serial.println(d.command);
  Serial.print("Source address: ");
  Serial.println(d.source_addr);
  Serial.print("Destination address: ");
  Serial.println(d.destination_addr);
  Serial.print("Payload: ");
  Serial.println(d.payload);
  
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
}// end recvWithStartEndMarkers


void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    command = atoi(strtokIndx);     // convert this part to an integer

    //strtok NULL means the indexing continue from previous operation
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(SA, strtokIndx); // copy it to SA

    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(DA, strtokIndx); // copy it to DA

    strtokIndx = strtok(NULL,",");      // get the first part - the string+
    payloadFromNANO = atoi(strtokIndx);

}//end parseData

void showParsedData() {
    Serial.println("----PARSED DATA--------------------------");
    Serial.print("Command ");
    Serial.println(command);
    Serial.print("SA ");
    Serial.println(SA);
    Serial.print("DA ");
    Serial.println(DA);
    Serial.print("Payload ");
    Serial.println(payloadFromNANO);
    Serial.println("-----------------------------------------");
    Serial.println("");
}//end parsed data

#endif // _STATION_FUNCTION_H

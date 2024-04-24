#ifndef _STATION_FUNCTION_H
#define _STATION_FUNCTION_H



void mcu_operation(struct parsedData);
void water_all();
void water_plot(struct parsedData myStruc);
void txData_HC12(char* s);
void display_struct(struct parsedData d);
void reqData_HC12();


void reqData_HC12(int* iterate_command)
{
  const char* command_3_A = "<3, smcu1, fmcu1, A>";
  const char* command_3_B = "<3, smcu1, fmcu1, B>";
  const char* command_3_C = "<3, smcu1, fmcu2, C>";
  const char* command_3_D = "<3, smcu1, fmcu2, D>";
  const char* command_3_E = "<3, smcu1, fmcu3, E>";
  const char* command_3_F = "<3, smcu1, fmcu3, F>";
  const char* command_3_G = "<3, smcu1, fmcu4, G>";
  const char* command_3_H = "<3, smcu1, fmcu4, H>";

  std::vector<const char*> packets = {command_3_A, command_3_B, command_3_C, command_3_D, command_3_E, command_3_F, command_3_G, command_3_H};
  
  Serial.print("To send to Field: ");
  Serial.println(packets[*iterate_command]);
  Serial.println("---------------------");
  //iterate each byte in string for transmission

  for(int i=0; i < strlen(packets[*iterate_command]); i++)
  {
    HC12.write(packets[*iterate_command][i]);
  }
  
  if(*iterate_command == MAXPLOT)
  {
    req_moisture_data = false;
    *iterate_command = 0;
  }
  else
  {
    *iterate_command = *iterate_command + 1;
  }
    
}

void parseJsonData(String data_from_ws)
{

  StaticJsonDocument<256> doc; //256 bytes buffer
  // Deserialize the JSON document
  DeserializationError error = deserializeJson(doc, data_from_ws);

  // Test if parsing succeeds.
  if (error) {
    Serial.print(F("deserializeJson() failed: "));
    Serial.println("");
    Serial.print(data_from_ws);
    Serial.println("");
    Serial.println(error.c_str());
    return;
  }

  myData.command = doc["C"];
  strcpy(myData.source_addr, doc["SA"]);
  strcpy(myData.destination_addr, doc["DA"]);
  strcpy(myData.plot_id, doc["PLOT_ID"]);
  myData.payload = doc["P"];
//  int command = doc["C"];
//  const char * sa = doc["SA"];
//  const char * da = doc["DA"];
//  int payload = doc["P"];
  
}

void parseDataFromHC12(struct parsedData *myData) {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    myData->command = atoi(strtokIndx);     // convert this part to an integer

    //strtok NULL means the indexing continue from previous operation
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(myData->source_addr, strtokIndx); // copy it to SA

    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(myData->destination_addr, strtokIndx); // copy it to DA

    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(myData->plot_id, strtokIndx); // copy it to plot_id

    strtokIndx = strtok(NULL,",");      // get the first part - the string+
    myData->payload = atoi(strtokIndx);



}//end parseDataFromHC12

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


void mcu_operation(struct parsedData myStruct)
{

  StaticJsonDocument<200> doc;
  
  
  switch (myStruct.command)
  {
    case 1:
      water_all();
      break;

    case 2:

      water_plot(myStruct);

      //activate the water pump based on the required remainder
      //ex: (desired) - (current) --> 1400 - 500 = 900
      //activate pump duration(time) associate with 900 (need experiment)
      // activate relay based on the required duration

      
      break;

    case 3:
    {

      String jsonString;
  
      doc["C"] = myStruct.command;
      doc["SA"] = myStruct.source_addr;
      doc["DA"] = myStruct.destination_addr;
      doc["PLOT_ID"] = myStruct.plot_id;
      doc["P"] = myStruct.payload;
  
      size_t len = serializeJson(doc, jsonString);
    
      //station request data from field
      //format between HC12
      
      //ex: String HC12_data = <command,SA,DA,payload>
      char formattedString[50]; //temp string variable
      //payload from Ws (decimal) --> payload process by ESP (decimal) --> parsed at field (dec -> char using ascii)
//      sprintf(formattedString, "<%d,%s,%s,%d>",myStruct.command, myStruct.source_addr, myStruct.destination_addr, myStruct.plot_id, myStruct.payload);
//      txData_HC12(formattedString);  //transmit what content of string
      Serial.println(jsonString);
      socket.send(jsonString.c_str(),len);
      
      break;
     }

//    case 4:
//    {
//      //myStruct.payload ==> the difference to reach threshold
//      //if threshold = 30000 & current_val = 23000, payload = 7000
//      //need some experiment to know how many seconds to reach 7000
//      int duration = 0;
//      //sensors detect low moisture level
//      if(myStruct.destination_addr != MCU_ID)
//      {
//        break;
//      }
//
//      if(myStruct.payload =< acceptable_threshold_margin)
//      {
//        //aim to avoid small difference to execute..irrelevant
//        break;
//      }
//      
//      digitalWrite(relay_pins[myStruct.plot_id], HIGH);
//      digitalWrite(pump_pins[myStruct.plot_id], HIGH);
//      delay(duration);
//       
//      break;
//    }

    case 5:
      //update field microcontroller eeprom data
      break;

    default:
      //undetermined command
      Serial.println("Unknown command!");
      break;
  }
  
}


void display_struct(struct parsedData d)
{
  Serial.println("");
  Serial.println("Parsed data from WS");
  Serial.print("Command: ");
  Serial.println(d.command);
  Serial.print("Source address: ");
  Serial.println(d.source_addr);
  Serial.print("Destination address: ");
  Serial.println(d.destination_addr);
  Serial.print("Plot ID: ");
  Serial.println(d.plot_id);
  Serial.print("Payload: ");
  Serial.println(d.payload);
  Serial.println("");     
  
}


void water_all()
{
  Serial.println("");
  Serial.println("Watering all plots....");
  Serial.println("");
}

void water_plot(struct parsedData myStruct)
{
  //need calculation based on the moisture_diff (payload)
  int watering_duration = 2000;
  
  digitalWrite(watering_mechanism[myStruct.plot_id].relay_pins,HIGH);
  digitalWrite(watering_mechanism[myStruct.plot_id].pump_pins,HIGH);
  watering_mechanism[myStruct.plot_id].watering_status = true;
  watering_mechanism[myStruct.plot_id].prev_time = millis();
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
                doneExecuteOperation = false;
            }
        }

        else if (rc == startMarker) {
            recvInProgress = true;
        }
    }
}// end recvWithStartEndMarkers




void showParsedData(struct parsedData d) {
    Serial.println("----PARSED DATA--------------------------");
    Serial.print("Command ");
    Serial.println(d.command);
    Serial.print("SA ");
    Serial.println(d.source_addr);
    Serial.print("DA ");
    Serial.println(d.destination_addr);
    Serial.print("Plot ID ");
    Serial.println(d.plot_id);
    Serial.print("Payload ");
    Serial.println(d.payload);
    Serial.println("-----------------------------------------");
    Serial.println("");
}//end parsed data

#endif // _STATION_FUNCTION_H

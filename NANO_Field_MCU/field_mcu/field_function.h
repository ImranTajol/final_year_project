#ifndef _FIELD_FUNCTION_H
#define _FIELD_FUNCTION_H


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

void remove_spaces (char* str_trimmed, const char* str_untrimmed)
{
  while (*str_untrimmed != '\0')
  {
    if(!isspace(*str_untrimmed))
    {
      *str_trimmed = *str_untrimmed;
      str_trimmed++;
    }
    str_untrimmed++;
  }
  *str_trimmed = '\0';
}


void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

//    strtokIndx = strtok(tempChars,",");      // get the first part - the string
//    strcpy(messageFromPC, strtokIndx); // copy it to messageFromPC
// 
//    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
//    integerFromPC = atoi(strtokIndx);     // convert this part to an integer
//
//    strtokIndx = strtok(NULL, ",");
//    floatFromPC = atof(strtokIndx);     // convert this part to a float

    //=============================================

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    command = atoi(strtokIndx);     // convert this part to an integer

    //strtok NULL means the indexing continue from previous operation
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(SA, strtokIndx); // copy it to SA

    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(DA, strtokIndx); // copy it to DA

    strtokIndx = strtok(NULL,",");      // get the first part - the string+
    strcpy(payloadFromESP, strtokIndx);
//    payloadFromESP = atoi(strtokIndx);
    
}


int check_source_address()
{
  if(SA != NULL) //source addr not empty
  {
    return 1;
  }
  else //destination addr empty
  {
    return 0;
  }
  
}

int check_destination_address()
{
  //strcmp return 0 if both string equal
  if(strcmp(mcu_id,DA) == 0)
  {
    return 1;
  }
  else
  {
    return 0;
  }
}

uint16_t read_soil_moisture(char* payloadFromESP)
{
  //check payload with address
  if(strcmp(payloadFromESP,PLOT_1) == 0)  //compare char
  {
    //read plot A moisture
    delay(300);
    Serial.println("Read Plot A");

//    uint16_t adc0 = 4;
    uint16_t adc1 = 4;
    uint16_t adc2 = 4;
    uint16_t adc3 = 4;
    uint16_t adc0 = ads1.readADC_SingleEnded(0)/4;
//    uint16_t adc1 = ads1.readADC_SingleEnded(1)/4;
//    uint16_t adc2 = ads1.readADC_SingleEnded(2)/4;
//    uint16_t adc3 = ads1.readADC_SingleEnded(3)/4;

    return adc0+adc1+adc2+adc3;
    delay(300);
  }
  if(strcmp(payloadFromESP,PLOT_2) == 0)
  {
    //read plot B moisture
    uint16_t adc0 = 5;
    uint16_t adc1 = 5;
    uint16_t adc2 = 5;
    uint16_t adc3 = 5;
//    uint16_t adc0 = ads2.readADC_SingleEnded(0)/4;
//    uint16_t adc0 = ads2.readADC_SingleEnded(0)/4;
//    uint16_t adc1 = ads2.readADC_SingleEnded(1)/4;
//    uint16_t adc2 = ads2.readADC_SingleEnded(2)/4;
//    uint16_t adc3 = ads2.readADC_SingleEnded(3)/4;

    return adc0+adc1+adc2+adc3;
    delay(300);
  }
  
}//end read soil moisture func
 
void formatData(char* dataToTx, uint8_t* C, char* SA, char* DA, char* payloadFromESP, uint16_t* payload)
{
  //combine the string and put it into dataToTx char array using the pointer
  //need *C in the sprintf because %c require char.. C is the address. *C to dereference it
  //SA and DA no need *... because it will dereference the first element in the array
  //ex: if char[] SA="SA".....*SA -> 'S' instead of "SA"
  sprintf(dataToTx,"<%u,%s,%s,%s,%u>", *C, SA, DA, payloadFromESP, *payload);
  
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


//constantly read
void all_time_read_moisture(Adafruit_ADS1115& adsInstance1, Adafruit_ADS1115& adsInstance2)
{
    uint16_t adc1 = (adsInstance1.readADC_SingleEnded(0) + adsInstance1.readADC_SingleEnded(1) + adsInstance1.readADC_SingleEnded(2) + adsInstance1.readADC_SingleEnded(3))/4;
    uint16_t adc2 = (adsInstance2.readADC_SingleEnded(0) + adsInstance2.readADC_SingleEnded(1) + adsInstance2.readADC_SingleEnded(2) + adsInstance2.readADC_SingleEnded(3))/4;
    uint16_t threshold = 22875;
    uint8_t command_low_water = 4;
    char plot1[2] = "A";
    char plot2[2] = "B";
    delay(300);

    if(adc1 > threshold){
      Serial.println("ADC1 is LOW ON WATER");

      formatData(formattedData, &command_low_water, mcu_id, mcu_id, plot1, &adc1);
      txData_HC12(formattedData);
      trigger_adhoc = true;
      check_adhoc = false; //need to check after water
      adhoc_time = millis();
    }
    else if(adc2 > threshold){
      Serial.println("ADC2 is LOW ON WATER");

      formatData(formattedData, &command_low_water, mcu_id, mcu_id, plot2, &adc2);
      txData_HC12(formattedData);
      trigger_adhoc = true;
      check_adhoc = false;
      adhoc_time = millis();
    }
    else{
      trigger_adhoc = false;
      check_adhoc = true;
    }
  
}




void showParsedData() {
    Serial.println("----PARSED DATA--------------------------");
    Serial.print("Command ");
    Serial.println(command);
    Serial.print("SA ");
    Serial.println(SA);
    Serial.print("DA ");
    Serial.println(DA);
    Serial.print("Payload ");
    Serial.println(payloadFromESP);
    Serial.println("-----------------------------------------");
    Serial.println("");
}


#endif // _FIELD_FUNCTION_H

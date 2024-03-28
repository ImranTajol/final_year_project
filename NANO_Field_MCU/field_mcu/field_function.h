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

    strtokIndx = strtok(NULL,",");      // get the first part - the string
    payloadFromESP = atoi(strtokIndx);     // convert this part to an integer

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
  if(DA == mcu_id)
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
  if(*payloadFromESP == plot1)
  {
    uint16_t adc0 = ads1.readADC_SingleEnded(0)/4;
    uint16_t adc1 = ads1.readADC_SingleEnded(1)/4;
    uint16_t adc2 = ads1.readADC_SingleEnded(2)/4;
    uint16_t adc3 = ads1.readADC_SingleEnded(3)/4;

    return adc0+adc1+adc2+adc3;
    
  }
  if(*payloadFromESP == plot2)
  {
    uint16_t adc0 = ads2.readADC_SingleEnded(0)/4;
    uint16_t adc1 = ads2.readADC_SingleEnded(1)/4;
    uint16_t adc2 = ads2.readADC_SingleEnded(2)/4;
    uint16_t adc3 = ads2.readADC_SingleEnded(3)/4;

    return adc0+adc1+adc2+adc3;
    
  }
  
}//end read soil moisture func

void formatData(char* dataToTx, char* C, char* SA, char* DA, uint16_t* payload)
{
  //combine the string and put it into dataToTx char array using the pointer
  //need *C in the sprintf because %c require char.. C is the address. *C to dereference it
  sprintf(dataToTx,"<%c,%c,%c,%u>", *C, *SA, *DA, *P);
  
}

void txData_HC12(char* s)
{
  //iterate each byte in string for transmission
  for(int i=0; i < strlen(s): i++)
  {
    HC12.write(s[i]);
  }
  
}




void showParsedData() {
    Serial.print("Command ");
    Serial.println(command);
    Serial.print("SA ");
    Serial.println(SA);
    Serial.print("DA ");
    Serial.println(DA);
    Serial.print("Payload ");
    Serial.println(payload);
}


#endif // _FIELD_FUNCTION_H

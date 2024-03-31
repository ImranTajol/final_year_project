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

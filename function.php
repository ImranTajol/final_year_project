
<script>
    
    //var socket = new WebSocket('ws://IP of PC at port 81(based on websocket server setting)');
    var socket = new WebSocket('ws://192.168.43.7:81');

    //placeholder
    var mcu_id = 'main'

    
    socket.onmessage = function(event)
    {
        console.log(event.data);
    }
    
//=============================================================================
    // Command 1: water all plots
    // command 2: water specific plot
    // Command 3: station request data from field
    // command 4: sensors detect low moisture level
    // command 5: update field microcontroller eeprom data
//=============================================================================




// button to water all plots (command = 1)
    function water_all()
    {
        // different command different action for watering system
        var command = 1;

        //need function to retrieve data from database for each plot

        var data = JSON.stringify({"C":command,"SA":button_value,"DA":mcu_id,"P":intval(water_duration)})
        socket.send(data)
    }


    // button to water specific plot (command = 2)
    function water_plot()
    {
        // different command different action for watering system
        var command = 2;

        //get water button plot A or B or C etc..
        var button_value = document.getElementById('water_button').value;
        // socket.send('Button value: '+ button_value+'\n');

        //get the watering duration
        var duration = document.getElementById('water_duration').value;
        var water_duration =parseInt(duration);
        // socket.send('Watering Duration: '+ water_duration+'\n');

        // var formatData = '|'+command+'|'+mcu_id+'|'+button_value+'|'+water_duration;
        var data = JSON.stringify({"C":command,"SA":button_value,"DA":mcu_id,"P":water_duration})
        socket.send(data)
    }


    function req_field_data()
    {
        var command = 3;

        var data = JSON.stringify({"C":command,"SA":button_value,"DA":mcu_id,"P":water_duration})

    }


    function update_eeprom()
    {
        var command = 5;

        var data = JSON.stringify({"C":command,"SA":button_value,"DA":mcu_id,"P":water_duration})

        //send data to mcu then perform checking whether its their data or not. (broadcast)

    }

    function update_db()
    {
        //create connection
        <?php include "db_connect.php";
        
        //$conn from db_connect.php
         $db = $conn;
        
        
        ?>


        //extract data from form (since this function.php is imported into the edit_data.php)
        //we can use the get element by ID
        var vegeType = document.getElementById('vegeType').value;
        var datePlant = document.getElementById('datePlant').value;
        var microcontrollerID = document.getElementById('microcontrollerID').value;

        <?php

        $stmt_log = $db->prepare("INSERT INTO farm_details (plant_type, mcu_id, date_plant) VALUES (?, ?, ?)");
				$stmt_log->bind_param('sssiis', $barcode, $tape_sender, $tape_receiver, $branch_id, $tape_status,$currentTimestamp);

        ?>
    }


</script>

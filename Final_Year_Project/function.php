
<script>
    
    //var socket = new WebSocket('ws://IP of PC at port 81(based on websocket server setting)');
    var socket = new WebSocket('ws://192.168.1.102:81');

    //placeholder
    var MCU_ID = 'smcu1';

    
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

        var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":"*","P":intval(water_duration)})
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

        // var formatData = '|'+command+'|'+MCU_ID+'|'+button_value+'|'+water_duration;
        var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":button_value,"P":water_duration})
        socket.send(data)
    }


    function req_field_data()
    {
        var command = 3;

        var data = JSON.stringify({"C":command,"SA":button_value,"DA":MCU_ID,"P":water_duration})

    }


    function update_eeprom()
    {
        var command = 5;

        var data = JSON.stringify({"C":command,"SA":button_value,"DA":MCU_ID,"P":water_duration})

        //send data to mcu then perform checking whether its their data or not. (broadcast)

    }


</script>

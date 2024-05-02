<style>
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

.data_display {
  font-size: 20px;
  font-weight: bold;
}

h1 {
    color: black; /* Dark green color */
    font-size: 36px; /* Adjust font size as needed */
    font-weight: bold; /* Make the text bold */
    text-align: center; /* Center the text */
    text-transform: uppercase; /* Convert text to uppercase */
    margin-top: 50px; /* Add margin at the bottom for spacing */
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}


</style>

<?php

$current_date = new DateTime();
$current_date->setTimezone(new DateTimeZone('Asia/Jakarta'));

include "db_connect.php";


//latest query (9/4/2024)
//to display at home page.. getting the last created plot info (latest crop planted)
$get_farm_details_A = "SELECT * FROM `farm_details` WHERE plot_id = 'A' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_B = "SELECT * FROM `farm_details` WHERE plot_id = 'B' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_C = "SELECT * FROM `farm_details` WHERE plot_id = 'C' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_D = "SELECT * FROM `farm_details` WHERE plot_id = 'D' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_E = "SELECT * FROM `farm_details` WHERE plot_id = 'E' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_F = "SELECT * FROM `farm_details` WHERE plot_id = 'F' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_G = "SELECT * FROM `farm_details` WHERE plot_id = 'G' ORDER BY date_created DESC LIMIT 1";
$get_farm_details_H = "SELECT * FROM `farm_details` WHERE plot_id = 'H' ORDER BY date_created DESC LIMIT 1";



//creating list
$home_page_display = [
  ['A', $conn->query($get_farm_details_A)->fetch_assoc()],
  ['B', $conn->query($get_farm_details_B)->fetch_assoc()],
  ['C', $conn->query($get_farm_details_C)->fetch_assoc()],
  ['D', $conn->query($get_farm_details_D)->fetch_assoc()],
  ['E', $conn->query($get_farm_details_E)->fetch_assoc()],
  ['F', $conn->query($get_farm_details_F)->fetch_assoc()],
  ['G', $conn->query($get_farm_details_G)->fetch_assoc()],
  ['H', $conn->query($get_farm_details_H)->fetch_assoc()]

];

?>


<h1>Programmable IoT Watering System</h1>

      <div class='square-container'>

      <!-- water all plots button -->
        <button class="btn btn-primary"  onclick="water_all()">Water All</button>

        <?php
          // Divide the $home_page_display array into two parts
          $first_half = array_slice($home_page_display, 0, count($home_page_display) / 2);
          $second_half = array_slice($home_page_display, count($home_page_display) / 2);
          ?>

          <div class="row">
              <!-- Display the first four items -->
              <?php foreach ($first_half as $plot): ?>
                  <div class="square">
                      <h3>Plot <?php echo $plot[0]; ?></h3>
                      <div class="inner-div">Vege Type: <span class="data_display"><?php echo $plot[1]["plant_type"]; ?></span> </div>
                      <div class="inner-div">Moist Level:  <span id="label_<?php echo $plot[0]; ?>" class="data_display"><?php echo $plot[1]["moisture_lvl"]; ?></span></div>
                      <div class="inner-div">Plant Age: 
                          <span class="data_display">
                              <?php 
                              $date_plant = new DateTime($plot[1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
                              $diff = $date_plant->diff($current_date)->format("%a");
                              echo $diff." days";
                              ?>
                          </span>
                      </div> 
                      <div class="inner-div">MCU ID: <span class="data_display"><?php echo $plot[1]["mcu_id"]; ?></div>
                      <div>
                          <button name="water_plot" value="<?php echo $plot[0]; ?>" class="btn btn-primary">Water</button>
                          <a href="./index.php?page=edit_data&button_id=<?php echo $plot[0]; ?>" class="btn btn-success">Edit</a>
                      </div>
                  </div>
              <?php endforeach; ?>
          </div>

          <div class="row">
              <!-- Display the next four items -->
              <?php foreach ($second_half as $plot): ?>
                  <div class="square">
                      <h3>Plot <?php echo $plot[0]; ?></h3>
                      <div class="inner-div">Vege Type: <span class="data_display"><?php echo $plot[1]["plant_type"]; ?></span> </div>
                      <div class="inner-div">Moist Level:  <span id="label_<?php echo $plot[0]; ?>" class="data_display"><?php echo $plot[1]["moisture_lvl"]; ?></span></div>
                      <div class="inner-div">Plant Age: 
                          <span class="data_display">
                              <?php 
                              $date_plant = new DateTime($plot[1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
                              $diff = $date_plant->diff($current_date)->format("%a");
                              echo $diff." days";
                              ?>
                          </span>
                      </div> 
                      <div class="inner-div">MCU ID: <span class="data_display"><?php echo $plot[1]["mcu_id"]; ?></div>
                      <div>
                          <button name="water_plot" value="<?php echo $plot[0]; ?>" class="btn btn-primary">Water</button>
                          <a href="./index.php?page=edit_data&button_id=<?php echo $plot[0]; ?>" class="btn btn-success">Edit</a>
                      </div>
                  </div>
              <?php endforeach; ?>
          </div>

    </div>


    

  <script>

    //placeholder
    var MCU_ID = 'smcu1'; //station MCU 1

    var socket = new WebSocket('ws://192.168.1.9:81');

    socket.onmessage = function(event)
    {
      
      console.log(event.data);
      resp = JSON.parse(event.data);
      console.log(resp["C"]);
      console.log(resp["SA"]);
      console.log(resp["DA"]);
      console.log(resp["PLOT_ID"]);
      console.log(resp["P"]);
      
      let label = "label_";
      document.getElementById(label.concat(resp["PLOT_ID"])).innerHTML = resp["P"];

      //add function to store to log
      switch(resp["C"])
      {
        case 3:
          //create log for moisture level(including update at farm details table)
          //aim: display the homepage usin 1 table
          store_to_log((resp["PLOT_ID"]), (resp["P"])); //args: plot id and moisture reading from field
          break;

        default:
          console.log("Default at switch(resp[C])");
          break;

      }



    }


    function redirect(letter) {
      // Redirect to another page based on the clicked letter
      window.location.href = 'redirect.php?letter=' + letter;
    }

    function sendToWebSocket_command_1(payload)
    {
      var command = 1;

      //watering operation send to ESP32 as it controls the pump and valves
      var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":MCU_ID, "P":payload})
      socket.send(data);
    }


    function sendToWebSocket_command_2(mcu_id, plot_id, payload)
    {
      var command = 2;

      //watering operation send to ESP32 as it controls the pump and valves
      var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":MCU_ID, "PLOT_ID":plot_id, "P":payload})
      socket.send(data);
    }

    // function sendToWebSocket_command_4(mcu_id, plot_id, payload)
    // {
    //   var command = 4;

    //   //DA = smcu1 because esp32 control the pump and valves
    //   var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":MCU_ID, "PLOT_ID":plot_id, "P":payload})
    //   socket.send(data);
    // }

    function store_to_log(plot_id, moisture_lvl)
    {

      var formData = {
        plot_id: plot_id,
        moisture_lvl: moisture_lvl,
      };

      $.ajax({
      url: 'ajax.php?action=store_log',
      data: formData,
      method: 'POST',
      success: function(resp) {
        resp = JSON.parse(resp);
        if(resp.status == "success")
        {
          //print data for debug
          console.log(resp.message);
          auto_water_plot(plot_id); //exec next function upon successful (water specific plot)
          //setTimeout(function() {location.href = "./index.php";},2000)
        }
        else
        {
          console.log("Store log failed!");
          setTimeout(function() {location.href = location.href;},3000)
          header("Refresh:0")

        }
      },
      error: function(xhr, status, error) {
        console.log("error to execute func");
        console.log(xhr.responseText); // Log any server-side errors for debugging
      }
      });

    }

    
    //feedback after 15 min interval of sensing moisture
    function auto_water_plot(plot_id)
    {
      
      var formData = {
        plot_id: plot_id,
      };
      
      $.ajax({
        url: 'ajax.php?action=water_plot',
        data: formData,
        method: 'POST',
        success: function(resp) {
          resp = JSON.parse(resp);
          if(resp.status == "success")
          {
            //print data for debug
            console.log("Auto water after sense moisture");
            console.log(resp.mcu_id);
            console.log(resp.plot_id);
            console.log(resp.moisture_level);
            
            sendToWebSocket_command_2(resp.mcu_id, resp.plot_id, resp.moisture_level);
            //setTimeout(function() {location.href = "./index.php";},2000)
          }
          else
          {
            console.log("Automated watering failed!");
            setTimeout(function() {location.href = location.href;},3000)
            header("Refresh:0")
            
          }
        },
        error: function(xhr, status, error) {
          console.log("error to execute func");
          console.log(xhr.responseText); // Log any server-side errors for debugging
        }
      });
      
    }




    
    function water_all()
    {
      var plot_ids = ['A','B','C','D','E','F','G','H'];
      var resultObj ={};
      var completedRequests = 0;
      

      for (let i = 0; i < plot_ids.length; i++) 
      {
        var formData = {
          plot_id: plot_ids[i],
        }

        $.ajax({
        url: 'ajax.php?action=water_plot',
        data: formData,
        method: 'POST',
        success: function(resp) {
          // console.log(resp);
          resp = JSON.parse(resp);
          if(resp.status == "success")
          {
            //print data for debug
            // console.log(resp.mcu_id);
            // console.log(resp.plot_id);
            // console.log(resp.moisture_level);
            
            sendToWebSocket_command_2(resp.mcu_id, resp.plot_id, resp.moisture_level);

            //setTimeout(function() {location.href = "./index.php";},2000)
            
          }
          else
          {
            console.log("Data entry failed!");
            setTimeout(function() {location.href = location.href;},3000)
            header("Refresh:0")

          }
        },
        error: function(xhr, status, error) {
          console.log("error to execute func");
          console.log(xhr.responseText); // Log any server-side errors for debugging
        }
        });

      }

    }




    $(document).ready(function() {
    
    $('button[name="water_plot"]').click(function(e) {
    e.preventDefault();


    var formData = {
      plot_id: $(this).val(),
    };

    
    $.ajax({
        url: 'ajax.php?action=water_plot',
        data: formData,
        method: 'POST',
        success: function(resp) {
          console.log(resp);
          resp = JSON.parse(resp);
          if(resp.status == "success")
          {
            //print data for debug
            console.log(resp.mcu_id);
            console.log(resp.plot_id);
            console.log(resp.moisture_level);
            
            sendToWebSocket_command_2(resp.mcu_id, resp.plot_id, resp.moisture_level);

            //setTimeout(function() {location.href = "./index.php";},2000)
            
          }
          else
          {
            console.log("Data entry failed!");
            setTimeout(function() {location.href = location.href;},3000)
            header("Refresh:0")

          }
        },
        error: function(xhr, status, error) {
          console.log("error to execute func");
          console.log(xhr.responseText); // Log any server-side errors for debugging
        }
        });

        })
      
    });


  </script>
<style>
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

</style>

<?php

$current_date = new DateTime();
$current_date->setTimezone(new DateTimeZone('Asia/Jakarta'));

include "db_connect.php";

$get_latest_moisture_A = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'A' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_B = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'B' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_C = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'C' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_D = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'D' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_E = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'E' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_F = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'F' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_G = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'G' ORDER BY date_created DESC LIMIT 1";
$get_latest_moisture_H = "SELECT moisture_lvl FROM `moisture_log` WHERE plot_id = 'H' ORDER BY date_created DESC LIMIT 1";


//latest query (9/4/2024)
$get_farm_details_A = "SELECT * FROM `farm_details` WHERE plot_id = 'A'";
$get_farm_details_B = "SELECT * FROM `farm_details` WHERE plot_id = 'B'";

$home_page_display = [
  ['A', $conn->query($get_farm_details_A)->fetch_assoc()],
  ['B', $conn->query($get_farm_details_B)->fetch_assoc()],
  ['C', $conn->query($get_latest_moisture_C)->fetch_column()],
  ['D', $conn->query($get_latest_moisture_D)->fetch_column()],
  ['E', $conn->query($get_latest_moisture_E)->fetch_column()],
  ['F', $conn->query($get_latest_moisture_F)->fetch_column()],
  ['G', $conn->query($get_latest_moisture_G)->fetch_column()],
  ['H', $conn->query($get_latest_moisture_H)->fetch_column()]

];

?>


<h1>Agriculture Automation System</h1>

      <div class='square-container'>

      <!-- water all plots button -->
        <button class="btn btn-primary"  onclick="water_all()">Water All</button>


        <div class="row">

            <div class="square">
                <h3>Plot A</h3>
                <div class="inner-div">Vege Type: <span><?php echo $home_page_display[0][1]["plant_type"]; ?></span> </div>
                <div class="inner-div">Moist Level:  <span id="label_A"><?php echo $home_page_display[0][1]["moisture_lvl"]; ?></span></div>
                <div class="inner-div">Plant Age: 
                  <span>
                    <?php $date_plant = new DateTime($home_page_display[0][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
                  
                    $diff = $date_plant->diff($current_date)->format("%a");

                    echo $diff." days";
                    ?>
                  </span>
                </div> 
                <div class="inner-div">MCU ID: <span><?php echo $home_page_display[0][1]["mcu_id"]; ?></div>
                <div>

                <!-- 1. button when click redirect to index.php and get the page
                      2. at the page, GET the id (respective to the button) -->
                    <button name="water_plot" value="A" class="btn btn-primary">Water plot (test)</button>
                    <a href="./index.php?page=water_manual&button_id=A" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=A" class="btn btn-success">Edit</a>
                    <!-- <a href="edit_data.php" class="btn btn-success">Edit</a> -->


                </div>
            </div>

            <div class="square">
              <h3>Plot B</h3>
              <div class="inner-div">Vege Type: <span><?php echo $home_page_display[1][1]["plant_type"]; ?></span> </div>
                  <div class="inner-div">Moist Level:  <span id="label_B"><?php echo $home_page_display[1][1]["moisture_lvl"]; ?></span></div>
                  <div class="inner-div">Plant Age: 
                  <span>
                    <?php $date_plant = new DateTime($home_page_display[1][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
                  
                    $diff = $date_plant->diff($current_date)->format("%a");

                    echo $diff." days";
                    ?>
                  </span>
                </div> 
                <div class="inner-div">MCU ID: <span><?php echo $home_page_display[1][1]["mcu_id"]; ?></div>

                <div>

                  <button name="water_plot" value="B" class="btn btn-primary">Water plot (test)</button>
                  <a href="./index.php?page=water_manual&button_id=B" class="btn btn-primary">Water</a>
                  <a href="./index.php?page=edit_data&button_id=B" class="btn btn-success">Edit</a>

                </div>
                
            </div>

            <div class="square">
              <h3>Plot C</h3>
                  <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level:</div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
                  <div>

                  <a href="./index.php?page=water_manual&button_id=C" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=C" class="btn btn-success">Edit</a>

                  </div>

            </div>


            <div class="square">
              <h3>Plot D</h3>
                <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level:</div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
                  <div>

                  <a href="./index.php?page=water_manual&button_id=D" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=D" class="btn btn-success">Edit</a>

                  </div>
            </div>

            <!-- row-wrap -->
        </div>

        <div class="row">

        <div class="square">
          
          
          <h3>Plot E</h3>
          <div class="inner-div">Vege Type:</div>
          <div class="inner-div">Moist Level:</div>
          <div class="inner-div">Plant Age:</div> 
          <div class="inner-div">MCU ID:</div>
          <div>
            <a href="./index.php?page=water_manual&button_id=E" class="btn btn-primary">Water</a>
            <a href="./index.php?page=edit_data&button_id=E" class="btn btn-success">Edit</a>
          </div>
          
        </div>


        <div class="square">

          
          <h3>Plot F</h3>
          <div class="inner-div">Vege Type:</div>
          <div class="inner-div">Moist Level:</div>
          <div class="inner-div">Plant Age:</div> 
          <div class="inner-div">MCU ID:</div>
          <div>
            
            <a href="./index.php?page=water_manual&button_id=F" class="btn btn-primary">Water</a>
            <a href="./index.php?page=edit_data&button_id=F" class="btn btn-success">Edit</a>
            
          </div>
          
        </div>


        <div class="square">

        
              <h3>Plot G</h3>
                <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level:</div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
                  <div>

                  <a href="./index.php?page=water_manual&button_id=G" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=G" class="btn btn-success">Edit</a>

                  </div>

        </div>


        <div class="square">
          
              <h3>Plot H</h3>
                <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level:</div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
                  <div>

                  <a href="./index.php?page=water_manual&button_id=H" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=H" class="btn btn-success">Edit</a>

                  </div>

        </div>

      </div>
        
        
    </div>


    

  <script>

    //placeholder
    var MCU_ID = 'smcu1'; //station MCU 1

    var socket = new WebSocket('ws://192.168.1.102:81');

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

    function sendToWebSocket(mcu_id, plot_id, payload)
    {
      var command = 2;

      //plot id send to websocket in term of ascii...ex: plot A -> 65
      //convert back the ascii at the receiver (esp or nano)
      var data = JSON.stringify({"C":command,"SA":MCU_ID,"DA":mcu_id, "PLOT_ID":plot_id.charCodeAt(0), "P":payload})
      socket.send(data);
    }

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
            console.log(resp.plot_id.charCodeAt(0));
            console.log(resp.moisture_level);
            
            sendToWebSocket(resp.mcu_id, resp.plot_id, resp.moisture_level);

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
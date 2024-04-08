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


<h1>Agriculture Automation System</h1>

      <div class='square-container'>

      <!-- water all plots button -->
        <button class="btn btn-primary"  onclick="water_all()">Water All</button>


        <div class="row">

        <?php $vege = "Potato"; ?>

            <div class="square">
                <h3>Plot A</h3>
                <div class="inner-div"><span>Vege Type:</span> </div>
                <div class="inner-div">Moist Level: <span id="label_A"></span></div>
                <div class="inner-div">Plant Age:</div> 
                <div class="inner-div">MCU ID:</div>
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
                <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level: <span id="label_B"></span></div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
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
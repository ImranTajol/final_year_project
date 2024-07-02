

<div class="row">

<div class="square">
    <h3>Plot <?php echo $home_page_display[0][0]; ?></h3>
    <div class="inner-div">Vege Type: <span class="data_display"><?php echo $home_page_display[0][1]["plant_type"]; ?></span> </div>
    <div class="inner-div">Moist Level:  <span id="label_A" class="data_display"><?php echo $home_page_display[0][1]["moisture_lvl"]; ?></span></div>
    <div class="inner-div">Plant Age: 
      <span class="data_display">
        <?php $date_plant = new DateTime($home_page_display[0][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
      
        $diff = $date_plant->diff($current_date)->format("%a");

        echo $diff." days";
        ?>
      </span>
    </div> 
    <div class="inner-div">MCU ID: <span class="data_display"><?php echo $home_page_display[0][1]["mcu_id"]; ?></div>
    <div>

    <!-- 1. button when click redirect to index.php and get the page
          2. at the page, GET the id (respective to the button) -->
        <button name="water_plot" value="A" class="btn btn-primary">Water</button>
        <a href="./index.php?page=edit_data&button_id=A" class="btn btn-success">Edit</a>
        <!-- <a href="edit_data.php" class="btn btn-success">Edit</a> -->


    </div>
</div>

<div class="square">
  <h3>Plot B</h3>
  <div class="inner-div">Vege Type: <span class="data_display"><?php echo $home_page_display[1][1]["plant_type"]; ?></span> </div>
      <div class="inner-div">Moist Level:  <span id="label_B" class="data_display"><?php echo $home_page_display[1][1]["moisture_lvl"]; ?></span></div>
      <div class="inner-div">Plant Age: 
      <span class="data_display">
        <?php $date_plant = new DateTime($home_page_display[1][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
      
        $diff = $date_plant->diff($current_date)->format("%a");

        echo $diff." days";
        ?>
      </span>
    </div> 
    <div class="inner-div">MCU ID: <span class="data_display"><?php echo $home_page_display[1][1]["mcu_id"]; ?> </span> </div>

    <div>

      <button name="water_plot" value="B" class="btn btn-primary">Water</button>
      <a href="./index.php?page=edit_data&button_id=B" class="btn btn-success">Edit</a>

    </div>
    
</div>

<div class="square">
  <h3>Plot C</h3>
      <div class="inner-div">Vege Type: <span class="data_display"><?php echo $home_page_display[2][1]["plant_type"]; ?></span></div>
      <div class="inner-div">Moist Level: <span id="label_C" class="data_display"><?php echo $home_page_display[2][1]["moisture_lvl"]; ?></span> </div>
      <div class="inner-div">Plant Age: 
        <span class="data_display">
          <?php $date_plant = new DateTime($home_page_display[2][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
        
          $diff = $date_plant->diff($current_date)->format("%a");

          echo $diff." days";
          ?>
        </span>
      </div> 
      <div class="inner-div">MCU ID: <span class="data_display"><?php echo $home_page_display[2][1]["mcu_id"]; ?> </span></div>
      <div>

      <button name="water_plot" value="C" class="btn btn-primary">Water</button>
      <a href="./index.php?page=edit_data&button_id=C" class="btn btn-success">Edit</a>

      </div>

</div>


<div class="square">
  <h3>Plot D</h3>
    <div class="inner-div">Vege Type:<span class="data_display"><?php echo $home_page_display[3][1]["plant_type"]; ?></span></div>
      <div class="inner-div">Moist Level:<span id="label_D" class="data_display"><?php echo $home_page_display[3][1]["moisture_lvl"]; ?></span></div>
      <div class="inner-div">Plant Age:
        <span class="data_display">
            <?php $date_plant = new DateTime($home_page_display[3][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
          
            $diff = $date_plant->diff($current_date)->format("%a");

            echo $diff." days";
            ?>
        </span>
      </div> 
      <div class="inner-div">MCU ID: <span class="data_display"><?php echo $home_page_display[3][1]["mcu_id"]; ?> </span></div>
      <div>

        <button name="water_plot" value="D" class="btn btn-primary">Water</button>
        <a href="./index.php?page=edit_data&button_id=D" class="btn btn-success">Edit</a>

      </div>
</div>

<!-- row-wrap -->
</div>

<div class="row">

<div class="square">


<h3>Plot E</h3>
<div class="inner-div">Vege Type:<span class="data_display"><?php echo $home_page_display[4][1]["plant_type"]; ?></span></div>
<div class="inner-div">Moist Level:<span id="label_E" class="data_display"><?php echo $home_page_display[4][1]["moisture_lvl"]; ?></span></div>
<div class="inner-div">Plant Age:
<span class="data_display">
  <?php $date_plant = new DateTime($home_page_display[4][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));

  $diff = $date_plant->diff($current_date)->format("%a");

  echo $diff." days";
  ?>
</span>
</div> 
<div class="inner-div">MCU ID:<span class="data_display"><?php echo $home_page_display[4][1]["mcu_id"]; ?> </span></div>
<div>
<button name="water_plot" value="E" class="btn btn-primary">Water</button>
<a href="./index.php?page=edit_data&button_id=E" class="btn btn-success">Edit</a>
</div>

</div>


<div class="square">


<h3>Plot F</h3>
<div class="inner-div">Vege Type:<span class="data_display"><?php echo $home_page_display[5][1]["plant_type"]; ?></span></div>
<div class="inner-div">Moist Level:<span id="label_F" class="data_display"><?php echo $home_page_display[5][1]["moisture_lvl"]; ?></span></div>
<div class="inner-div">Plant Age:
<span class="data_display">
    <?php $date_plant = new DateTime($home_page_display[5][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
  
    $diff = $date_plant->diff($current_date)->format("%a");

    echo $diff." days";
    ?>
</span>
</div> 
<div class="inner-div">MCU ID:<span class="data_display"><?php echo $home_page_display[5][1]["mcu_id"]; ?> </span></div>
<div>
<button name="water_plot" value="F" class="btn btn-primary">Water</button>
<a href="./index.php?page=edit_data&button_id=F" class="btn btn-success">Edit</a>

</div>

</div>


<div class="square">


  <h3>Plot G</h3>
    <div class="inner-div">Vege Type:<span class="data_display"><?php echo $home_page_display[6][1]["plant_type"]; ?></span></div>
      <div class="inner-div">Moist Level:<span id="label_G" class="data_display"><?php echo $home_page_display[6][1]["moisture_lvl"]; ?></span></div>
      <div class="inner-div">Plant Age:
        <span class="data_display">
            <?php $date_plant = new DateTime($home_page_display[6][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
          
            $diff = $date_plant->diff($current_date)->format("%a");

            echo $diff." days";
            ?>
        </span>
      </div> 
      <div class="inner-div">MCU ID:<span class="data_display"><?php echo $home_page_display[6][1]["mcu_id"]; ?> </span></div>
      <div>
        <button name="water_plot" value="G" class="btn btn-primary">Water</button>
        <a href="./index.php?page=edit_data&button_id=G" class="btn btn-success">Edit</a>

      </div>

</div>


<div class="square">

  <h3>Plot H</h3>
    <div class="inner-div">Vege Type:<span class="data_display"><?php echo $home_page_display[7][1]["plant_type"]; ?></span></div>
      <div class="inner-div">Moist Level:<span id="label_H" class="data_display"><?php echo $home_page_display[7][1]["moisture_lvl"]; ?></span></div>
      <div class="inner-div">Plant Age:
        <span class="data_display">
            <?php $date_plant = new DateTime($home_page_display[7][1]["date_plant"], new DateTimeZone('Asia/Jakarta'));
          
            $diff = $date_plant->diff($current_date)->format("%a");

            echo $diff." days";
            ?>
        </span>
      </div> 
      <div class="inner-div">MCU ID:<span class="data_display"><?php echo $home_page_display[7][1]["mcu_id"]; ?> </span></div>
      <div>

        <button name="water_plot" value="H" class="btn btn-primary">Water</button>
        <a href="./index.php?page=edit_data&button_id=H" class="btn btn-success">Edit</a>

      </div>

</div>

</div>
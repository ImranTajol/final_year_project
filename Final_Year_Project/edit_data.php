<?php include "function.php";

      include "db_connect.php";

$button_id = $_GET['button_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

  <title>Field Details</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .form-container {
      width: 300px;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
    }
    .form-container input[type="text"],
    .form-container textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
    }
    .form-container input[type="submit"],
    .form-container input[type="button"] {
      padding: 8px 12px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      background-color: #4caf50;
      color: white;
    }
    .form-container input[type="submit"]:hover,
    .form-container input[type="button"]:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
<div class="form-container">
    <form id="plant_data" method="POST">
        <input type="hidden" id="plot_id" name="plot_id" value=<?php echo $button_id?>>
        
        <div class="form-group">
          <label for="selected_vege">Vegetable Type:</label>
          <select name="selected_vege" id="selected_vege" class="form-control">
            <option value=""></option>
            <?php 
                $vege = $conn->query("SELECT DISTINCT crop_type FROM registered_crop");
                while ($row = $vege->fetch_assoc()):
                  ?>
                <option value="<?php echo $row['crop_type'] ?>" >
                  <?php echo ucwords($row['crop_type']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            
            <div class="form-group">
                <label for="datePlant">Date Plant:</label>
                <input required class="form-control" type="date" id="datePlant" name="datePlant">
            </div>
            
            <div class="form-group">
            <label for="selected_mcu">Microcontroller ID:</label>
            <select name="selected_mcu" id="selected_mcu" class="form-control">
                <option value=""></option>
                <?php 
                $mcu_id = $conn->query("SELECT * FROM registered_mcu");
                while ($row = $mcu_id->fetch_assoc()):
                ?>
                <option value="<?php echo $row['mcu_id'] ?>" <?php echo isset($from_mcu_id) && $from_mcu_id == $row['id'] ? "selected" : '' ?>>
                    <?php echo $row['mcu_id'] ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="d-flex justify-content-center">
            <button class="btn btn-success mr-2" form="plant_data">Save</button>
            <a class="btn btn-danger" href="./index.php?">Cancel</a>
        </div>
    </form>
</div>




</body>
</html>

<script>

  

  $('#plant_data').submit(function(e) {
    e.preventDefault();


    var formData = {
      plot_id: $("#plot_id").val(),
      selected_vege:$("#selected_vege").val(),
      datePlant: $("#datePlant").val(),
      selected_mcu: $("#selected_mcu").val(),
      // vegeType: $("#vegeType").val(),
      // microcontrollerID: $("#microcontrollerID").val(),

    };
    
  $.ajax({
      url: 'ajax.php?action=edit_data',
      data: formData,
      method: 'POST',
      success: function(resp) {
        console.log(resp);
        resp = JSON.parse(resp);
        if(resp.status == "success")
        {
          setTimeout(function() {location.href = "./index.php";},2000)
          
        }
        else
        {
          console.log("Data entry failed!");
          setTimeout(function() {location.href = location.href;},3000)
          header("Refresh:0")

        }
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText); // Log any server-side errors for debugging
      }
  });

  });

  



</script>

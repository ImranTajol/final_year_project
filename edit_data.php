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
    <form id = "plant_data" method="POST">

      <label for="vegeType">Vege Type:</label>
      <input type="text" id="vegeType" name="vegeType">

      <label for="datePlant">Date Plant:</label>
      <input type="text" id="datePlant" name="datePlant">

      <label for="microcontrollerID">Microcontroller ID:</label>
      <input type="text" id="microcontrollerID" name="microcontrollerID">

    </form>

    <div>
      <div class="d-flex w-100 justify-content-center align-items-center">
          <button class="btn btn-success" form="plant_data">Save</button>
          <a class="btn btn-danger" href="./index.php?">Cancel</a>
      </div>
    </div>

  </div>



</body>
</html>

<script>

function sendDataToESP32() {
    var jsonData = {
        vegeType: 'YourVegeTypeValue',
        datePlant: 'YourDatePlantValue',
        microcontrollerID: 'YourMicrocontrollerIDValue'
    };

    var xhr = new XMLHttpRequest();
    var url = 'http://192.168.137.1/test'; // Replace with your ESP32 IP and endpoint
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Data sent successfully to ESP32');
                // Handle success response (if needed)
            } else {
                console.error('Failed to send data to ESP32');
                // Handle error (if needed)
            }
        }
    };

    xhr.send(JSON.stringify(jsonData));
}



  $('#plant_data').submit(function(e) {
    e.preventDefault();

    sendDataToESP32();


  //   var formData = {
  //     vegeType: $("#vegeType").val(),
  //     datePlant: $("#datePlant").val(),
  //     microcontrollerID: $("#microcontrollerID").val(),
  //   };
    
  // $.ajax({
  //     url: 'ajax.php?action=edit_data',
  //     data: formData,
  //     method: 'POST',
  //     success: function(resp) {
  //         console.log(resp);
  //     },
  //     error: function(xhr, status, error) {
  //       console.log(xhr.responseText); // Log any server-side errors for debugging
  //     }
  // });

  });

  



</script>

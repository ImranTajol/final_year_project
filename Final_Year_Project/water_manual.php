<?php include "function.php";

$button_id = $_GET['button_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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
  <div id="water_manual_div" class="form-container">
    <form id="water_manual" action="#">

      <label for="water_duration">Duration (in seconds):</label>
      <input type="text" id="water_duration" name="water_duration">

      
    </form>

    <div>
      <div class="d-flex w-100 justify-content-center align-items-center">
          <button class="btn btn-success" id = 'water_button' value=<?php echo $button_id?> onclick="water_plot()">Water</button>
          <a class="btn btn-danger" href="./index.php?">Cancel</a>
      </div>
  </div>
  </div>

</body>
</html>

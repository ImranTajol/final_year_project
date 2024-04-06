
<!DOCTYPE html>
<html>
<head>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<link rel="stylesheet" href="styles.css">

  <title>IoT Programmable Vegetable Farm</title> 
  <style>
    /* Styling for the squares */
    .square-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 50px; /* Adjust spacing */
    }

    .row {
      display: flex;
      justify-content: space-between;
    }

    .square {
        width: 400px;
        height: 400px;
        background-color: lightblue;
        margin: 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        cursor: pointer;
    }

    .inner-div {
        width: 80%;
        height: 10%;
        background-color: lightblue;
        border: 1px solid #ccc;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
  </style>
</head>


<body>

    <?php 
      $page = isset($_GET['page']) ? $_GET['page'] : 'home';
      if(!file_exists($page.".php")){
          include '404.html';
      }else{
      include $page.'.php';
      }
    ?>
    <!-- if at url index.php, it will redirect to home page -->
    <!-- in case we have test.php file, index.php?page=test will redirect to test.php page -->

</body>
</html>

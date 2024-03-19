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
                <div class="inner-div">Vege Type: <?php echo $vege?> </div>
                <div class="inner-div">Moist Level:</div>
                <div class="inner-div">Plant Age:</div> 
                <div class="inner-div">MCU ID:</div>
                <div>

                <!-- 1. button when click redirect to index.php and get the page
                      2. at the page, GET the id (respective to the button) -->
                    <a href="./index.php?page=water_manual&button_id=A" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=A" class="btn btn-success">Edit</a>
                    <!-- <a href="edit_data.php" class="btn btn-success">Edit</a> -->

                    <!-- POPUP TESTING
                    <button type="submit" class="btn btn-primary" onclick="openpopup()">Water Test</button>

                    <div class="popup" id="popup">
                      <div id="water_manual_div">Test</div>
                      <h2>Thank You</h2>
                      <p>The form</p>

                      <button type="button" onclick="closepopup()">OKAY</button>
                    </div> -->
                    

                </div>
            </div>

            <div class="square" onclick="redirect('B')">
              <h3>Plot B</h3>
                <div class="inner-div">Vege Type:</div>
                  <div class="inner-div">Moist Level:</div>
                  <div class="inner-div">Plant Age:</div> 
                  <div class="inner-div">MCU ID:</div>
                  <div>

                    <a href="./index.php?page=water_manual&button_id=B" class="btn btn-primary">Water</a>
                    <a href="./index.php?page=edit_data&button_id=B" class="btn btn-success">Edit</a>

                </div>
            </div>

            <div class="square" onclick="redirect('C')">
          
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
            <div class="square" onclick="redirect('D')">
          
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

            <div class="square" onclick="redirect('E')">
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

            <div class="square" onclick="redirect('F')">
            <div class="square" >
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

            <div class="square" onclick="redirect('G')">
          
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
            <div class="square" onclick="redirect('H')">
          
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

    <button class="open-button" onclick="openForm()">Open Form</button>

<div class="form-popup" id="myForm">
  <form action="/action_page.php" class="form-container">
    <h1>Login</h1>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <button type="submit" class="btn">Login</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div>

  <script>
    function redirect(letter) {
      // Redirect to another page based on the clicked letter
      window.location.href = 'redirect.php?letter=' + letter;
    }

    function openForm() {
      document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
      document.getElementById("myForm").style.display = "none";
    }


    let popup =document.getElementById("popup");

    function openpopup()
    {
        popup.classList.add("open-popup");
    }

    function closepopup()
    {
        popup.classList.remove("open-popup");
    }
  </script>
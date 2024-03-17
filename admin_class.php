<?php

Class Action {

    private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
	//$conn from db_connect.php
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}


    function edit_data(){

        include "db_connect.php";

        var_dump($_POST);
        extract($_POST);
        $vegeType = $_POST['vegeType'];
        $datePlant = $_POST['datePlant'];
        $microcontrollerID = $_POST['microcontrollerID'];


        $stmt_update = $conn->prepare("INSERT INTO farm_details (plot_id, plant_type, mcu_id, date_plant) VALUES (?, ?, ?, ?)");
        $stmt_update->bind_param('ssss', $plot_id, $vegeType, $microcontrollerID, $datePlant);

            // Execute the prepared statement
                if ($stmt_update->execute()) {

                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt_update->close();

        $conn->close();

        return json_encode(array("status" => "success", "message" => "Operation successful!!"));

    }


    function water_plot()
    {
    


        //--------------------------------
    }

}
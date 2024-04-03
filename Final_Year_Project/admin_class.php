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

        //mysql to find date difference
        //SELECT plot_id, date_plant, FLOOR(DATEDIFF(CURRENT_DATE, date_plant)) AS duration FROM farm_details;


        include "db_connect.php";

        extract($_POST);
        $vegeType = $_POST['vegeType'];
        $datePlant = $_POST['datePlant'];
        $microcontrollerID = $_POST['microcontrollerID'];

        if(empty($vegeType) || empty($datePlant) || empty($microcontrollerID))
        {
            return json_encode(array("status" => "error", "message" => "Data field cannot be empty. Please insert value!"));
        }


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
        //retrieve from db
        //date planted minus current date = time diff
        //q: how do i determine how long to water for 7/8/etc.. days for potato/etc...??

        // $current_date = date('Y-m-d');
        $current_date = new DateTime('Y-m-d');

        include "db_connect.php";

        extract($_POST);
        $plot_id = $_POST['plot_id'];   //containing which plot to water

        if(empty($plot_id)) //check if empty
        {
            return json_encode(array("status" => "error", "message" => "Data field cannot be empty. Please insert value!"));
        }

        
        
        $stmt_find_diff = $conn->prepare("SELECT * FROM farm_details WHERE plot_id=?");
        $stmt_find_diff->bind_param("s", $id);
        $stmt_find_diff->execute();
        $result = $stmt_find_diff->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data   
        
        $crop = $user['plant_type']."_crop";

        //find date diff
        $diff = date_diff($current_date, $user["date_plant"]);

        $stmt_find_level = $conn->prepare("SELECT moisture_level FROM $crop WHERE crop_day < ? ORDER BY crop_day DESC LIMIT 1");
        $stmt_find_level->bind_param("i", $diff);
        $stmt_find_diff->execute();
        $result = $stmt_find_diff->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data   

        // SELECT `id`, `crop_day`, `moisture_level`, `date_created` FROM `potato_crop` WHERE crop_day < 21 ORDER BY crop_day DESC limit 1


    }

}
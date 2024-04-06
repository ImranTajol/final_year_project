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
        $current_date = new DateTime();
        $current_date->setTimezone(new DateTimeZone('Asia/Jakarta'));

        include "db_connect.php";

        extract($_POST);

        $plot_id = $_POST['plot_id'];   //containing which plot to water

        // var_dump($_POST);

        if(empty($plot_id)) //check if empty
        {
            return json_encode(array("status" => "error", "message" => "Data field cannot be empty. Please insert value!"));
        }
        
        $stmt_find_diff = $conn->prepare("SELECT * FROM farm_details WHERE plot_id=?");
        $stmt_find_diff->bind_param("s", $plot_id);
        $stmt_find_diff->execute();
        $result = $stmt_find_diff->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data   

        $mcu_id = $user["mcu_id"];
        $crop = $user['plant_type']."_crop";

        $date_plant = new DateTime($user["date_plant"], new DateTimeZone('Asia/Jakarta')); //create datetime object using retrieved date (string)

        //find date diff in terms of days(%d)..ex: 2024/3/19->diff(2024/4/4)->format("%d") = 16 days
        $diff = $date_plant->diff($current_date)->format("%a");

        $stmt_find_level = $conn->prepare("SELECT moisture_level FROM $crop WHERE crop_day < ? ORDER BY crop_day DESC LIMIT 1");
        $stmt_find_level->bind_param("s", $diff);
        $stmt_find_level->execute();
        $result = $stmt_find_level->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data   \

        $conn->close();

        return json_encode(array("status" => "success", "mcu_id" => $mcu_id, "moisture_level" => $user["moisture_level"], "plot_id" => $plot_id));


    }

}
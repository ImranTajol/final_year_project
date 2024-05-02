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
        $vegeType = ucwords($_POST['vegeType']);
        $datePlant = $_POST['datePlant'];
        // $microcontrollerID = $_POST['microcontrollerID'];
        $mcu_id = $_POST['selected_mcu'];

        if(empty($vegeType) || empty($datePlant) || empty($mcu_id))
        {
            return json_encode(array("status" => "error", "message" => "Data field cannot be empty. Please insert value!"));
        }


        $stmt_update = $conn->prepare("INSERT INTO farm_details (plot_id, plant_type, mcu_id, date_plant) VALUES (?, ?, ?, ?)");
        $stmt_update->bind_param('ssss', $plot_id, $vegeType, $mcu_id, $datePlant);

            // Execute the prepared statement
                if ($stmt_update->execute()) {

                } else {
                    echo "Error: " . $stmt_update->error;
                }

                $stmt_update->close();

        $conn->close();

        return json_encode(array("status" => "success", "message" => "Operation successful!!"));

    }


    function water_plot() //function will return the remaining moisture level needed (threshold - current)
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
        $current_moisture = $user["moisture_lvl"];

        $date_plant = new DateTime($user["date_plant"], new DateTimeZone('Asia/Jakarta')); //create datetime object using retrieved date (string)

        //find date diff in terms of days(%d)..ex: 2024/3/19->diff(2024/4/4)->format("%d") = 16 days
        $diff = $date_plant->diff($current_date)->format("%a");

        $stmt_find_level = $conn->prepare("SELECT moisture_level FROM $crop WHERE crop_day < ? ORDER BY crop_day DESC LIMIT 1");
        $stmt_find_level->bind_param("s", $diff);
        $stmt_find_level->execute();
        $result = $stmt_find_level->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data   \
        $threshold = $user["moisture_level"];

        $conn->close();

        $moisture_diff = $threshold - $current_moisture;

        return json_encode(array("status" => "success", "mcu_id" => $mcu_id, "moisture_level" =>  $moisture_diff, "plot_id" => $plot_id));


    }

    function store_log()
    {

        $current_date = new DateTime();
        $current_date->setTimezone(new DateTimeZone('Asia/Jakarta'));

        include "db_connect.php";

        extract($_POST);

        $plot_id = $_POST['plot_id'];
        $moisture_lvl = $_POST['moisture_lvl'];


        $stmt_insert_log = $conn->prepare("INSERT INTO moisture_log (plot_id, moisture_lvl) VALUES (?, ?)");
        $stmt_insert_log->bind_param('ss', $plot_id, $moisture_lvl);

        // Execute the prepared statement
        if ($stmt_insert_log->execute()) 
        {
            //if success do nothing
        } 

        else 
        {
            echo "Error: " . $stmt_insert_log->error;
        }

        $stmt_insert_log->close();

        //update farm details table
        // easier to display data in home page using single table
        $stmt_update_farm_details = $conn->prepare("UPDATE farm_details SET moisture_lvl= ? WHERE plot_id =  ?");
        $stmt_update_farm_details->bind_param('ds', $moisture_lvl,$plot_id);

        // Execute the prepared statement
        if ($stmt_update_farm_details->execute()) 
        {
            //if success do nothing
        } 

        else 
        {
            echo "Error: " . $stmt_update_farm_details->error;
        }

        $stmt_update_farm_details->close();

        $conn->close();

        return json_encode(array("status" => "success", "message" => "Store log operation successful!!"));
        
    }

}
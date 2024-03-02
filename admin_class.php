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

        extract($_POST);
        $vegeType = $_POST['vegeType'];
        $datePlant = $_POST['datePlant'];
        $microcontrollerID = $_POST['microcontrollerID'];

        return json_encode(array("status" => "success", "message" => "Operation successful!!"));

    }


    function water_plot()
    {
    


        //--------------------------------
    }

}
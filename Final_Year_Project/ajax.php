<?php

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'edit_data'){
	$login = $crud->edit_data();
	if($login)
		echo $login;
}

if($action == 'water_plot'){
	$login = $crud->water_plot();
	if($login)
		echo $login;
}

if($action == 'store_log'){
	$login = $crud->store_log();
	if($login)
		echo $login;
}

if($action == 'manual_water_plot'){
	$login = $crud->manual_water_plot();
	if($login)
		echo $login;
}
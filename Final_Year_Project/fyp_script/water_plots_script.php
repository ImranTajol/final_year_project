<?php

require 'vendor/autoload.php';

use WebSocket\Client;

$wsServerUrl = "ws://bk2011018-fyp-web-socket-server.glitch.me/";

$client = new Client($wsServerUrl, [
    'headers' => [
        'User-Agent' => 'Chrome'
    ]
]);

function sendToWebSocket_command_2($mcu_id, $plot_id, $plant_age, $moisture_level)
{
    $source_mcu = "smcu1";
    $command = 2;

    $data = json_encode([
        "C" => $command,
        "SA" => $source_mcu,
        "DA" => $mcu_id,
        "PLOT_ID" => $plot_id,
        "PLANT_AGE" => $plant_age,
        "P" => $moisture_level
    ]);

    return $data;

}

//need to run a function
$url = 'http://localhost/Final_Year_Project/ajax.php?action=water_plot';
// $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
$arr = ['A', 'B', 'C', 'D'];

foreach($arr as $a){
    
    $myvars = ['plot_id' => $a];
    
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    
    $response = curl_exec( $ch );
    $resp = json_decode($response,true);


    $tempData = sendToWebSocket_command_2($resp["mcu_id"],$resp["plot_id"],$resp["plant_age"],$resp["moisture_level"]);

    // echo $tempData;

    try {
        $client->send($tempData);
        // $response = $client->receive();
        // echo "Server response: $response\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    
    
}

$client->close();


<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class MyWebSocket implements MessageComponentInterface{

    public $clients;
    private $connectedClients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->connectedClients = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->connectedClients[$conn->resourceId] = $conn;
        echo"New connection! ({$conn->resourceId})\n";
        $conn->send('Welcome to the server.');

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Client: {$from->resourceId}: {$msg}\n";
        // echo 'Received at server: '.$msg. ' from Client: '.$from->resourceId.'\n';

        //// This line below send the message to all connected clients (broadcast)

        foreach($this->connectedClients as $client){
            
                echo "Client To All: {$from->resourceId}: {$msg} \n";
                $client->send($msg);

        }

    }

    public function onClose(ConnectionInterface $conn)
    {
        echo"Connection Disconnected!. ID: ({$conn->resourceId})\n";
        $conn->close();

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo'An error occured'.$e->getMessage(). '\n';
        $conn->close();
         
    }

}

#port 81 used... 65535 available port in a computer
#binding ip 0.0.0.0 --> server listen form any IP address(similar to subnet)
#using 'localhost' does not allow external client to connect.. put PC IP ADDRESS.
$app = new Ratchet\App('192.168.1.102',81,'0.0.0.0');
$app->route('/',new MyWebSocket,array('*'));

$app->run();

?>
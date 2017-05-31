<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('192.168.1.2', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$fn = $_GET['fn'];

$channel->queue_declare('CRM', false, true, false, false);
$userinfo = array(
	"action" => "CREATE",
	"first_name" => $fn,
	"last_name" => "bb",
	"adress" => "blablastraat"
);

$data = json_encode($userinfo);

$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($msg, '', 'CRM');

//header('Location: form.php?sent=true');

echo " [x] Sent ", $data, "\n";


$channel->close();
$connection->close();

?>
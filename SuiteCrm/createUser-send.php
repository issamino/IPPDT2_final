<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('10.3.51.38', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$channel->queue_declare('CRM', false, true, false, false);
$userinfo = array(
	"firstname" => "adam",
	"lastname" => "bb",
	"id" => 1,
	
);

$data = json_encode($userinfo);

$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($msg, '', 'CRM');

//header('Location: form.php?sent=true');

echo " [x] Sent ", $data, "\n";


$channel->close();
$connection->close();

?>
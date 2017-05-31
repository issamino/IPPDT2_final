<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once('CRMcommandos.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('10.3.51.38', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$channel->queue_declare('CRM', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
	$userinfo = json_decode($msg->body);
	

	

  echo " [x] Received ", $msg->body, "\n";
  
  
  //CREATE INTO CRM
  
  createAccount($userinfo[0]->firstname, $userinfo[0]->lastname, $userinfo[0]->id);
sleep(substr_count($msg->body, '.'));
  echo " [x] Done", "\n";
};

/**
* don't dispatch a new message to a worker until it has processed and 
* acknowledged the previous one. Instead, it will dispatch it to the 
* next worker that is not still busy.
*/
$channel->basic_qos(null, 1, null);

 /**
* indicate interest in consuming messages from a particular queue. When they do 
* so, we say that they register a consumer or, simply put, subscribe to a queue.
* Each consumer (subscription) has an identifier called a consumer tag
*/ 
$channel->basic_consume('CRM', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
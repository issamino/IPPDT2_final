<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once '/addInvoice.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('10.3.51.38', 5672, 'admin', 'admin124');
$channel = $connection->channel();
$channel->queue_declare('Invoices', false, true, false, false);
echo ' [*] Waiting for receiving invoices. To exit press CTRL+C', "\n";
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
	addInvoiceClient($msg->body);
};
$channel->basic_consume('Invoices', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
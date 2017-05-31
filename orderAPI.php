<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once '/PSWebServiceLibrary.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;



global $connection;
global $channel;

function newOrders($id)
{

$connection = new AMQPConnection('192.168.1.2', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$channel->queue_declare('Orders', false, true, false, false);

  $webService = new PrestaShopWebService('http://10.3.51.42/hotel2/intourist/','//2YJEYH5SF96CRBB97VR5CRT37KDZG4XZ',false);

$xml = $webService->get(array('resource'=>'orders','id'=>$id));
$ordersNodes = $xml->children()->children();
$orders = array();


  $customer = (string) $ordersNodes->customer;
  $total =  (string) $ordersNodes->total;
  $id = (int) $ordersNodes->id;
  $orders[] = array('customer' => $customer, 'total' => $total, 'id' => $id);


$data = json_encode($orders);




$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($msg, '', 'Orders');

//header('Location: form.php?sent=true');

echo " [x] Sent ", $data, "\n";


$channel->close();
$connection->close();
}
?>


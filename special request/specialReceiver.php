<?php
require_once ('C:\Users\Administrator\Desktop\comp\vendor\autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('10.3.51.38', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$channel->queue_declare('Special', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$con = mysqli_connect("localhost","root","","special");

$callback = function($msg) {
	global $con;
	$specialrequests = json_decode($msg->body);
	

  if($specialrequests[0]->action == "add")
  {
	  $sql = "INSERT INTO requests (user_id, request)
VALUES ('". $specialrequests[0]->userid. " ', '" . $specialrequests[0]->msg. "')";
	  mysqli_query($con,$sql);
		
		//mysqli_query($con,"SELECT * FROM requests;");
  echo " [x] Received ", $msg->body, "\n";

	 }
	 
	 
	 elseif($specialrequests[0]->action == "edit")
  {
	  $sql ="UPDATE requests SET request = '".$specialrequests[0]->msg."' WHERE request_id = '".$specialrequests[0]->reqid."'"; 
	   
	  mysqli_query($con,$sql);
		
		//mysqli_query($con,"SELECT * FROM requests;");
  echo " [x] Received ", $msg->body, "\n";

	 }
	 
	  
	 elseif($specialrequests[0]->action == "delete")
  {
	  $sql ="DELETE FROM  requests WHERE request_id = '".$specialrequests[0]->reqid."'"; 
	   
	  mysqli_query($con,$sql);
		
		//mysqli_query($con,"SELECT * FROM requests;");
  echo " [x] Received ", $msg->body, "\n";

	 }
	 
/**/
 
  
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
$channel->basic_consume('Special', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
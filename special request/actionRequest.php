<?php
require_once ('C:\Users\Administrator\Desktop\comp\vendor\autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


global $reqid;

if(isset($_POST['add']))
{
$uid = $_POST['customer'];
$msg = $_POST['special'];

msgRequest($uid,'add',$msg);
}

elseif(isset($_POST['edit']))
{
$uid = $_POST['uid'];
$reqid = $_POST['reqid'];
$msg = $_POST['req'];

msgRequest($uid, 'edit', $msg);

}
elseif(isset($_POST['delete']))
{
$uid = $_POST['uid'];
$reqid = $_POST['reqid'];
$msg = $_POST['req'];

msgRequest($uid, 'delete', $msg);

}

global $connection;
global $channel;

function msgRequest($uid, $action, $msg)
{
global $reqid;

$connection = new AMQPConnection('10.3.51.38', 5672, 'admin', 'admin124');
$channel = $connection->channel();

$channel->queue_declare('Special', false, true, false, false);

if( $action == 'add' )
{
 $request= array();
$request[] = array('userid' => $uid, 'msg' => $msg, 'action' => 'add');
 
}

elseif ( $action == "edit")
{
 $request= array();
$request[] = array('reqid'=>$reqid, 'userid' => $uid, 'msg' => $msg, 'action' => 'edit');

}

elseif ( $action == "delete")
{
$request= array();
$request[] = array('reqid'=>$reqid, 'userid' => $uid, 'msg' => $msg, 'action' => 'delete');
	
}
$data = json_encode($request);




$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($msg, '', 'Special');

//header('Location: form.php?sent=true');

echo " [x] Sent ", $data, "\n";


$channel->close();
$connection->close();
}



?>

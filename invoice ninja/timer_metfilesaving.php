<?php
require_once '/hotelInvoiceSender.php';
$relative_path = __DIR__ . '/info.txt';

$lastInvoiceID;
//sendNewCustomers();
readSavedInfos();
function sendNewInvoices()
{
   global $lastInvoiceID;
    do
    {
        
       $response =  Invoice($lastInvoiceID);
	
        if($response != false)
        {
           echo "phptimer true"; 
     
           
                
                
           $lastInvoiceID++; 
		   writeSavedInfos();
        }
		else {
		echo "phptimer false";}
    }while ($response != false);
    
   
}

/**/
 //readSavedInfos();
 //read the saved info so you dont have to reinit them
while(true)
{
    sendNewInvoices();
    sleep(30);//every 30SEconds
}

//$relative_path = 'php/IP/info.txt';

function readSavedInfos()
{
    global $relative_path;
    global $lastInvoiceID;
    
    $myfile = fopen($relative_path, "r") or die("Unable to open file!");
    $info = fread($myfile,filesize($relative_path));
    fclose($myfile);
    
    $jsoninfo = json_decode($info,true);
    
    $lastInvoiceID = $jsoninfo["lastInvoiceID"];
 
    
}
function writeSavedInfos()
{
    global $relative_path;
    global $lastInvoiceID;
    
    $myfile = fopen($relative_path, "w") or die("Unable to open file!");
    $info = array(  "lastInvoiceID" => $lastInvoiceID,
    );
    fwrite($myfile, json_encode($info));
    fclose($myfile);

}
?>
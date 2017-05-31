<?php
require_once '/hotelInvoiceSender.php';

$lastInvoiceID = 1;
//sendNewCustomers();
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

?>
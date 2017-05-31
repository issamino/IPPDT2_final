<?php
require_once '/customerSender.php';
$lastCustomerID = 7;
//sendNewCustomers();
function sendNewCustomers()
{
   global $lastCustomerID;
    do
    {
        
       $response =  getCustomer($lastCustomerID);
	
        if($response != false)
        {
           //echo "phptimer true"; 
     
           
                sendCustomer($lastCustomerID);
                
           $lastCustomerID++; 
        }
		//else {}
			//echo "phptimer false";
    }while ($response != false);
    
   
}

/**/
 //readSavedInfos();
 //read the saved info so you dont have to reinit them
while(true)
{
    sendNewCustomers();
    sleep(30);//every 30SEconds
}
?>
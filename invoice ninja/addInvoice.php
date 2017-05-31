<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use InvoiceNinja\Config as NinjaConfig;
use InvoiceNinja\Models\Client;
use InvoiceNinja\Models\Invoice;
$id = "";
$client_id ="";

NinjaConfig::setURL('localhost/ninja/public/api/v1');
NinjaConfig::setToken('hmijpyfnbqobvwnybehswtdheqhqfsxs');

        function addInvoice($client,$price, $date){
        $invoice = $client->createInvoice();
        $invoice->addInvoiceItem('Hotelreservatie', 'Hotel ', $price);
        $invoice->due_date = $date;
        $invoice->save();   
        }

        function addItemToExistingInvoice($invoice_id,$price,$date){
        $invoice = Invoice::find($invoice_id);
        $invoice->addInvoiceItem('Hotelreservatie', 'Hotel', $price);
        $invoice->due_date = $date;
        $invoice->save(); 
        }

        function isJson($string) {
            return ((is_string($string) &&
                    (is_object(json_decode($string)) ||
                    is_array(json_decode($string))))) ? true : false;
        }


function addInvoiceClient($msg_json){
    
if(isJson($msg_json)){
            $json = json_decode($msg_json, true);
            $firstname = $json['firstname'];
            $lastname = $json['lastname'];
            $email = $json['email'];
            $name = $firstname . " " . $lastname;
            $price = (float)$json['total_price'];
            $date = (string)$json['date_add'];
    
$mysqli = new mysqli("localhost", "ninja", "ninja", "ninja");

/* Vérification de la connexion */
if ($mysqli->connect_errno) {
    printf("Échec de la connexion : %s\n", $mysqli->connect_error);
    exit();
}
    
$query = "SELECT client_id FROM contacts WHERE email ='".$email."'";


if ($result=mysqli_query($mysqli,$query))
  {
  // Return the number of rows in result set
  $rowcount=mysqli_num_rows($result);
  
        if($rowcount == 0){
            
            $client = new Client($email,$firstname,$lastname,$name);
            $client->save();
            addInvoice($client, $price, $date);
           
        }

else{
                   
            while ($row = mysqli_fetch_row($result)) {
                    $client_id = (int)$row[0];
            }
   
          
            $query2 = "SELECT id FROM invoices WHERE client_id ='".$client_id."' AND is_public = 0";
                    
                   if ($result2=mysqli_query($mysqli,$query2))
                    {
                        
                        // Return the number of rows in result set
                        $rowcount=mysqli_num_rows($result2);
                            var_dump($rowcount);
                        if($rowcount == 1){

                            while ($row= mysqli_fetch_row($result2)) {
                            $id = (int)$row[0];

                            }
                           
                            $client = Client::find($client_id);
                            $client->save();
                            addItemToExistingInvoice($id,$price,$date);

                        }
                        elseif($rowcount == 0){
                            $client = Client::find($client_id);
                          
                            $client->save();
                            addInvoice($client, $price, $date);
                        }
                        else{
                            echo 'Meerdere facturen';
                        }
                        

                    }
}
mysqli_close($mysqli);
        }
}
                        else
                        {
                        echo " no json object";
                    }

}

?>


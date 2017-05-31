<?php  
require_once '/PSWebServiceLibrary.php';

	$webService = new PrestaShopWebService('http://10.3.51.42/hotel2/intourist/','2YJEYH5SF96CRBB97VR5CRT37KDZG4XZ',false);
	?>
	<form action="actionRequest.php" method="post">

 
 <?php 
  


$customers['resource'] = 'customers';
$customers['display'] = 'full';
$xml = $webService->get($customers);
$customerNodes = $xml->customers->children();


 ?>
  Customer: 

 <select name="customer" >
  <?php
    foreach($customerNodes as $customer) { 
	 $firstname = $customer->firstname;
	 $id = (int) $customer->id;
	 
	?>
	
      <option value="<?php echo $id ?>"><?php echo $firstname ?></option>
  <?php
    } ?>
</select> 
 <br>
  
  Special request
  <input type="text" name="special">
  <input type="submit" name="add" value="Submit">
</form>
	



 
<form action="actionRequest.php" method="post">


  Customer: 

 
  <?php
    
	 $reqid = $_POST['reqid'];
	 $uid = $_POST['uid'];
	 $req = $_POST['req'];
	 
	?>
	
	Edit special request(<?php echo $reqid; ?>) of user(<?php echo $uid; ?>): <br>
	
	<input type="text" name="req" value="<?php echo $req; ?>">
	<input type="hidden" name="uid" value="<?php echo $uid ?>" />
	<input type="hidden" name="reqid" value="<?php echo $reqid ?>" />
  
 <br>

  <input type="submit" name="edit" value="edit">
</form>
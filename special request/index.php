<?php
include ('db.php');
include ('C:\xampp\htdocs\special\actionRequest.php');
$result = mysqli_query($con,"SELECT * FROM requests");

echo "<table border='1'>
<tr>
<th>Request ID</th>
<th>User ID</th>
<th>Request</th>
<th>Action</th>
</tr>";
global $reqid;
global $uid;
global $req;
while($row = mysqli_fetch_array($result))
{
	$reqid = $row['request_id'];
	$uid = $row['user_id'];
	$req = $row['request'];
echo "<tr>";
echo "<td>" . $reqid . "</td>";
echo "<td>" . $uid . "</td>";
echo "<td>" . $req . "</td>";
?><td>
<form action="edit.php" method="post">
          <p>
            <input type="submit" name="edit" value="edit" />
			<input type="hidden" name="reqid" value="<?php echo $reqid ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid ?>" />
			<input type="hidden" name="req" value="<?php echo $req ?>" />


          </p>      
        </form>
		<form action="actionRequest.php" method="post">
          <p>
            <input type="submit" name="delete" value="delete" />
			<input type="hidden" name="reqid" value="<?php echo $reqid ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid ?>" />
			<input type="hidden" name="req" value="<?php echo $req ?>" />


          </p>      
        </form>
	</td>	
</tr>

<?php
}
?>

<form action="add.php" method="post">
          <p>
            <input type="submit" name="add" value="add" />
			
          </p>      
        </form>
		
<?php
mysqli_close($con);
?>
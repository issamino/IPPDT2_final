<?php
error_reporting(0);
//log them out
$logout = $_GET['logout'];
if ($logout == "yes") { //destroy the session
	session_start();
	$_SESSION = array();
	session_destroy();
}

//force the browser to use ssl (STRONGLY RECOMMENDED!!!!!!!!)
if ($_SERVER["SERVER_PORT"] != 443){ 
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); 
    exit(); 
}

//you should look into using PECL filter or some form of filtering here for POST variables
$username = strtoupper($_POST["username"]); //remove case sensitivity on the username
$password = $_POST["password"];
$formage = $_POST["formage"];

if ($_POST["oldform"]) { //prevent null bind

	if ($username != NULL && $password != NULL){
		//include the class and create a connection
		include (dirname(__FILE__) . "/../src/adLDAP.php");
        try {
		    $adldap = new adLDAP();
        }
        catch (adLDAPException $e) {
            echo $e; 
            exit();   
        }
		
		//authenticate the user
		if ($adldap->authenticate($username, $password)){
			//establish your session and redirect
			session_start();
			$_SESSION["username"] = $username;
            $_SESSION["userinfo"] = $adldap->user()->info($username);
			$_SESSION["bing"] = $password;
			$redir = "Location: http://10.3.51.42/hotel2/admin5422e3q6p";
			
			header($redir);
			exit;
		}
	}
	$failed = 1;
}

?>

<html>
<head>
<title>adLDAP example</title>
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

<form class="sign-up" method='post' action='<?php echo $_SERVER["PHP_SELF"]; ?>'>
<h1 class="sign-up-title">Restricted Area</h1>
<input type='hidden' name='oldform' value='1'>

Username: <input  class="sign-up-input" type='text' name='username' value='<?php echo ($username); ?>'><br>
Password: <input  class="sign-up-input" type='password' name='password'><br>
<br>

<input class="sign-up-button" type='submit' name='submit' value='Submit'><br>
<?php if ($failed){ echo ("<br>Login Failed!<br><br>\n"); } ?>
</form>

<?php if ($logout=="yes") { echo ("<br>You have successfully logged out."); } ?>


</body>

</html>


<?php
session_start();
$nonavbar='';
$pagetitle='Login';
if (isset($_SESSION['Username'])){
	header('location: dashboard.php');	  // redirect to dashboard page
}
 include "init.php";



//check if user coming from http post request

if ($_SERVER['REQUEST_METHOD']=='POST'){

	$Username=$_POST['user'];
	$password=$_POST['pass'];
	$hashedpass=sha1($password);

	//check if user exist in database
	$stmt=$conn->prepare("SELECT 
							UserID, Username, Password 
						from 
							users 
						WHERE 
							Username = ? 
						and 
							Password = ? 
						and 
							GroupID = 1
							limit 1");
	$stmt->execute(array($Username, $hashedpass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	// echo $count;

	// if count >0 this mean the database contain record about this username

	if ($count > 0){
		$_SESSION['Username'] = $Username;    //register session name
		$_SESSION['ID'] = $row['UserID'];	  //register session ID
		header('location: dashboard.php');	  // redirect to dashboard page
		exit();

	}
}


 ?>





<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<h4 class="text-center">Admin Login</h4>
	<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
	<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
	<input  class="btn btn-primary btn-block" type="submit" value="Login" />

</form>






<?php include $tpl."footer.php"; ?>
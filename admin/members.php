<?php

// =================================================
// == manage members page 
// == you can add | edit | delete members from here
// =================================================

session_start();
$pagetitle = 'Members';
if (isset($_SESSION['Username'])){
	include 'init.php';    
    
	// $pagetitle='Dashboard';
	$do = '';
	if (isset($_GET['do'])){
		$do = $_GET['do'];
	}else{
		$do = 'Manage';
	}

	// start manage page

	if ($do == 'Manage'){  // Manage Page
        
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            
            $query = 'and RegStatus = 0';
        }
        
        
        

		//select all users except admin
		$stmt=$conn->prepare("SELECT * from users where GroupID != 1 $query order by UserID desc");
		$stmt->execute();

		//assign to variables
		$rows=$stmt->fetchAll();

        if(! empty($rows)){

	 ?>

		<h1 class="text-center">Manage Members</h1>
		<div class="container">
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>#ID</td>
					<td>Username</td>
					<td>Email</td>
					<td>Full name</td>
					<td>Registered Date</td>
					<td>Control</td>
				</tr>
				<?php

				foreach($rows as $row){
                    
					echo "<tr>";
						echo "<td>".$row['UserID']."</td>";
						echo "<td>".$row['Username']."</td>";
						echo "<td>".$row['Email']."</td>";
						echo "<td>".$row['Fullname']."</td>";
						echo "<td>".$row['Date']."</td>";
						echo "<td>
								<a href='?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
								<a href='?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                
                                if ($row['RegStatus'] == 0){
                                   echo "<a href='?do=Activate&userid=".$row['UserID']."' class='btn btn-info Activate'><i class='fa fa-check'></i> Activate</a>";
                                }
                                
							echo "</td>";
					
				}

				echo "</tr>";
				?>
				
			</table>
		</div>
			<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
		</div>
<?php }else{
           echo '<div class="container">';
           echo'<div class="nice-messege">There is on members to show</div>';
          echo'<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a> ';
            echo '</div>';
        } ?>
<?php
	}elseif ($do == 'Add') { ?>

	<h1 class="text-center">Add New Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- start username field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username to login into shop">
							</div>
						</div>
						<!-- end username field -->
						<!-- start password field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password must be hard and complex" required="required">
								<i class="show-pass fa fa-eye fa-2x"></i>
							</div>
						</div>
						<!-- end password field -->
						<!-- start Email field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="email" name="email" class="form-control" required="required" placeholder="Email must be valid">
							</div>
						</div>
						<!-- end Email field -->
						<!-- start Full Name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10">
								<input type="text" name="full" class="form-control" required="required" placeholder="Full name appear in your profile page">
							</div>
						</div>
						<!-- end Full Name field -->
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Member" class="btn btn-success btn-lg">
							</div>
						</div>
						<!-- end submit field -->

					</form>

				</div>

<?php
	}elseif ($do=='Insert') {
		//insert member page
	
	if ($_SERVER['REQUEST_METHOD']=='POST'){

		echo "<h1 class='text-center'>Insert Member</h1>";
		echo "<div class='container'>";

		//get variables from the form
		$user = $_POST['username'];
		$pass = $_POST['password'];
		$email = $_POST['email'];
		$name = $_POST['full'];
		
		$hashpass= sha1($pass);

		//validate the form

		$formerrors = array();

		if(strlen($user) < 4){
			$formerrors[] = 'Username cannot be less than<strong> 4 characters</strong>';
		}

		if(strlen($user) > 20){
			$formerrors[] = 'Username cannot be more than<strong> 20 characters</strong>';
		}

		if (empty($user)){
			$formerrors[] = 'Username cannot be <strong> Empty</strong>';
		}

		if (empty($pass)){
			$formerrors[] = 'Password cannot be <strong> Empty</strong>';
		}

		if (empty($name)){
			$formerrors[] = 'Full name cannot be <strong> Empty</strong>';
		}

		if (empty($email)){
			$formerrors[] = 'Email cannot be<strong> Empty</strong>';
		}
		// print all errors
		foreach ($formerrors as $error) {
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}

		//check if there is no errors proceed the update operation
		if(empty($formerrors)){
            
            //check if user exist in database
            $check = checkitem("Username","users",$user);
            if ($check == 1){
                    $themsg = "<div class='alert alert-danger'>sorry this user is exist</div>";
                    redirecthome($themsg,'back');
                echo '';
            }else {
                	//Insert into database

			$stmt=$conn->prepare("INSERT INTO users(Username,Password,Email,Fullname,RegStatus,Date)VALUES(:zuser, :zpass, :zmail, :zname,1, now() ) ");
			$stmt->execute(array(

				'zuser' => $user,
				'zpass' => $hashpass,
				'zmail' => $email,
				'zname' => $name

				));
		
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Inserted</div>';
                redirecthome($themsg,'back');
		}

            }
            
        
	}else {
        echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";

	}elseif ($do == 'Edit') {	# edit page  
		//check if get request userid is numeric & get the integer value of it
		if ( isset($_GET['userid']) && is_numeric($_GET['userid'])){
			$userid = intval($_GET['userid']);
		}else {
			$userid = 0;
		}
		// select all data depend on this id
		$stmt=$conn->prepare("SELECT * from users WHERE UserID = ? limit 1");
		//execute query
		$stmt->execute(array($userid));
		//fetch data from database
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		//if there is such id then show the form
		if ($count > 0) { ?>
				<h1 class="text-center">Edit Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="userid" value="<?php echo $userid; ?>">
					<!-- start username field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="text" name="username" value="<?php echo $row['Username']; ?>" class="form-control" autocomplete="off" required="required">
							</div>
						</div>
						<!-- end username field -->
						<!-- start password field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank if you Don't won't to change">
							</div>
						</div>
						<!-- end password field -->
						<!-- start Email field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="email" name="email" value="<?php echo $row['Email']; ?>" class="form-control" required="required">
							</div>
						</div>
						<!-- end Email field -->
						<!-- start Full Name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10">
								<input type="text" name="full" value="<?php echo $row['Fullname']; ?>" class="form-control" required="required">
							</div>
						</div>
						<!-- end Full Name field -->
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save"  class="btn btn-success btn-lg">
							</div>
						</div>
						<!-- end submit field -->

					</form>

				</div>

<?php
	//if there is no such id then show error messege
	} else {
             echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>There is no such ID</div>";
		redirecthome($themsg);
        echo "</div>";
			
		}
} elseif ($do == 'Update') { // update page
	echo "<h1 class='text-center'>Update Member</h1>";
	echo "<div class='container'>";
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		//get variables from the form
		$id = $_POST['userid'];
		$user = $_POST['username'];
		$email = $_POST['email'];
		$name = $_POST['full'];
		
		//password trick	
		$pass = '';
		if (empty($_POST['newpassword'])) {
			$pass = $_POST['oldpassword'];
		}else {
			$pass = sha1($_POST['newpassword']);
		}

		//validate the form

		$formerrors = array();

		if(strlen($user) < 4){
			$formerrors[] = 'Username cannot be less than<strong> 4 characters</strong>';
		}

		if(strlen($user) > 20){
			$formerrors[] = 'Username cannot be more than<strong> 20 characters</strong>';
		}

		if (empty($user)){
			$formerrors[] = 'Username cannot be <strong> Empty</strong>';
		}

		if (empty($name)){
			$formerrors[] = 'Full name cannot be <strong> Empty</strong>';
		}

		if (empty($email)){
			$formerrors[] = 'Email cannot be<strong> Empty</strong>';
		}
		// print all errors
		foreach ($formerrors as $error) {
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}

		//check if there is no errors proceed the update operation
		if(empty($formerrors)){
            $stmt2 = $conn->prepare("select * from users where Username = ? and UserID!=?");
            $stmt2->execute(array($user,$id));
            $count = $stmt2->rowcount();
            if($count==1){
                $themsg='<div class="alert alert-danger">. sorry this user is exist .</div>';
                redirecthome($themsg,'back');
            }else{
			//update database
		$stmt=$conn->prepare("update users set Username = ?,Email = ?,Fullname = ?,Password = ? where UserID=?");
		$stmt->execute(array($user,$email,$name,$pass,$id));
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Updated</div>';
		redirecthome($themsg,'back');
                }
        }


		
		
	}else {
         echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";
}elseif ($do == 'Delete') {

	echo "<h1 class='text-center'>Delete Member</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['userid']) && is_numeric($_GET['userid'])){
				$userid = intval($_GET['userid']);
			}else {
				$userid = 0;
			}
			// select all data depend on this id
			$stmt=$conn->prepare("SELECT * from users WHERE UserID = ? limit 1");
        
            $check = checkitem('userid','users' , $userid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("DELETE from users where UserID= :zuser");
				$stmt->bindparam(":zuser", $userid);
				$stmt->execute();

				$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Deleted</div>';
                 
		          redirecthome($themsg);
        

			}else{
                    echo "<div class='container'>";
                    $themsg = "<div class='alert alert-danger'>this id not exist</div>";
                    redirecthome($themsg);
                    echo "</div>";
				
			}
		echo "</div>";
}elseif($do == 'Activate') {
        echo "<h1 class='text-center'>Activate Member</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['userid']) && is_numeric($_GET['userid'])){
				$userid = intval($_GET['userid']);
			}else {
				$userid = 0;
			}
			// select all data depend on this id
			$stmt=$conn->prepare("SELECT * from users WHERE UserID = ? limit 1");
        
            $check = checkitem('userid','users' , $userid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("UPDATE users SET RegStatus = 1 where UserID = ?");
				$stmt->execute(array($userid));

				$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Activated</div>';
                 
		          redirecthome($themsg, 'back');
        

			}else{
                    echo "<div class='container'>";
                    $themsg = "<div class='alert alert-danger'>this id not exist</div>";
                    redirecthome($themsg);
                    echo "</div>";
				
			}
    }


	 include $tpl."footer.php";

}else {
	
	header('location: index.php');
	exit();
}


<?php

session_start();
$nonavbar='';
$pagetitle='Login';

if (isset($_SESSION['user'])){
	header('location: index.php');	  // redirect to index page
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD']=='POST'){
    
    if(isset($_POST['login'])){

	$user=$_POST['username'];
	$pass=$_POST['password'];
	$hashedpass=sha1($pass);

	//check if user exist in database
	$stmt=$conn->prepare("SELECT 
							Username, Password 
						from 
							users 
						WHERE 
							Username = ? 
						and 
							Password = ? 
						");
	$stmt->execute(array($user, $hashedpass));
	$count = $stmt->rowCount();
	// echo $count;

	// if count >0 this mean the database contain record about this username

	if ($count > 0){
		$_SESSION['user'] = $user;    //register session name
		header('location: index.php');	  // redirect to dashboard page
		exit();
    }
	}else{
        $formErrors = array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];
        if (isset($username)){
            $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
            if(strlen($filterdUser) < 4 ){
               $formErrors[] = 'Username must be larger than 4 characters'; 
            }
        }
        if (isset($password) && isset($password2)){
            if (empty($password)){
              $formErrors[] = 'sorry password can not be empty';  
            }
            $pass1 = sha1($password);
            $pass2 = sha1($password2);
            if($pass1 !== $pass2){
                $formErrors[] = 'Sorry password is not match';
            }
        }
         if (isset($email)){
            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){
                $formErrors[] = 'This email is not valid';
            }
        }
        //check if there is no errors proceed the user add
		if(empty($formerrors)){
            
            //check if user exist in database
            $check = checkitem("Username","users",$username);
            if ($check == 1){
                $formErrors[] = 'Sorry This user is exist';
            }else {
                	//Insert into database

			$stmt=$conn->prepare("INSERT INTO users(Username,Password,Email,RegStatus,Date)VALUES(:zuser, :zpass, :zmail,0, now() ) ");
			$stmt->execute(array(

				'zuser' => $username,
				'zpass' => sha1($password),
				'zmail' => $email

				));
		$successMsg = 'Congratulations you are now registered user';
		}

            }
    }
}




?>
    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
        
<!--        start login form-->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password">
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
            
        </form>
        
<!--        end login form -->
        
<!--        start signup form-->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input pattern=".{4,8}" title="username must be between 4 & 8 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required>
            </div>
            <div class="input-container">
                <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a complex password" required>
            </div>
            <div class="input-container">
                <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again" required>
            </div>
            <div class="input-container">
                <input class="form-control" type="email" name="email" placeholder="Type a valid email" required>
            </div>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup">
            
        </form>
<!--        end signup form-->
        <div class="the-errors text-center">
            <?php
                if(!empty($formErrors)){
                    foreach($formErrors as $error){
                        echo '<div class="msg error">' . $error . '</div>';
                    }
                }    
              if (isset($successMsg)){
                  echo '<div class="msg success">' . $successMsg . '</div>' ;
              }
            ?>
        </div>
    </div>


<?php 
include $tpl . 'footer.php';
?>

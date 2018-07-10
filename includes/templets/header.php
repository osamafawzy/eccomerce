<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php gettitle(); ?></title>
	<link rel="stylesheet"  href="<?php echo $css; ?>bootstrap.min.css">
	<link rel="stylesheet"  href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet"  href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet"  href="<?php echo $css; ?>jquery.selectBoxIt.css">
	<link rel="stylesheet"  href="<?php echo $css; ?>front.css">
</head>
<body>
    <div class="upper-bar">
        <div class="container">
            <?php
            if (isset($_SESSION['user'])){
	           echo 'Welcome '.$_SESSION['user'].' ';	  // redirect to index page
                echo '<a href="profile.php">My Profile</a>';
                echo ' - <a href="newad.php">New AD</a>';
                echo ' - <a href="logout.php">Logout</a>';
               $userstatus = checkuserstatus($_SESSION['user']);
                if($userstatus == 1){
                    // user is not active
                 //   echo 'you are not active';
                }
            }else{
            ?>
            <a href="login.php">
                <span class="pull-right">Login/Signup</span>
            </a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-nav">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Home Page</a>
    </div>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
        <?php
          $categories = getcat();
          foreach($categories as $cat){
              echo '<li><a href="categories.php?pageid=' .$cat['ID'] . '&pagename='.str_replace(' ','-',$cat['Name']) .'">' . $cat['Name'] . '</a></li>';
          }
          
          
          ?>
        
      </ul>
    </div>
  </div>
</nav>

<?php

    ob_start();   //output buffering start(for problem header already sent )

session_start();
if (isset($_SESSION['Username'])){

	$pagetitle='Dashboard';

//print_r($_SESSION);  print all sessions
	

	include 'init.php';
    
//    start dashboard page
     $numUsers = 5;
     $latestUsers = getlatest("*", "users","UserID",$numUsers);
    
     
     $numItems = 5;
    $latestItems = getlatest("*","items","Item_ID",$numItems);
    
    $numComments = 5;
    
    
    ?>
<div class="home-stats">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                       <div class="info">
                         Total Members
                        <span><a href="members.php"><?php echo countitems('UserID', 'users') ?></a></span>
                      </div>
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                       <div class="info">
                             Pending Members
                        <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkitem("RegStatus", "users",0) ?>
                            </a></span>
                        </div>
                     </div>
                </div>
                 <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                        <span><a href="items.php"><?php echo countitems('Item_ID', 'items') ?></a></span>
                        </div>
                     </div>
                </div>
                 <div class="col-md-3">
                    <div class="stat st-comments">
                       <i class="fa fa-comments"></i>
                        <div class="info">
                             Total Comments
                            <span><a href="comments.php"><?php echo countitems('c_id', 'comments') ?></a> </span>
                        </div>
                     </div>
                </div>
            </div>

        </div>
</div>

<div class="latest">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                        <?php  
                        if(! empty ($latestUsers)){
                        foreach($latestUsers as $user){
                            echo '<li>'.$user['Username'] .
                                '<a href="members.php?do=Edit&userid='.$user['UserID'].'">'
                                .'<span class="btn btn-success pull-right">
                                <i class="fa fa-edit"></i> Edit'; 
                            
                             if ($user['RegStatus'] == 0){
                                   echo "<a href='members.php?do=Activate&userid=".$user['UserID']."' class='btn btn-info Activate pull-right'><i class='fa fa-check'></i> Activate</a>";
                                }
                            echo '</span></a></li>';
                        }
                        }else{
                                echo 'There is no members to show';
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                        <?php  
                         if (!empty ($latestItems)){
                        foreach($latestItems as $item){
                            echo '<li>'.$item['Name'] .
                                '<a href="items.php?do=Edit&itemid='.$item['Item_ID'].'">'
                                .'<span class="btn btn-success pull-right">
                                <i class="fa fa-edit"></i> Edit'; 
                            
                             if ($item['Approve'] == 0){
                                   echo "<a href='items.php?do=Approve&itemid=".$item['Item_ID']."' class='btn btn-info Activate pull-right'><i class='fa fa-check'></i> Approve</a>";
                                }
                            echo '</span></a></li>';
                        }
                        }else{
                                echo 'There is no items to show';
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
<!--        start latest comment-->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comments-o"></i> Latest <?php echo $numComments; ?> Comments
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <?php
                            $stmt=$conn->prepare("SELECT comments.*, users.Username AS Member
                                                    from comments
                                                    INNER JOIN users ON users.UserID = comments.user_id
                                                    order by c_id desc
                                                    limit $numComments
                                                    ");
                            $stmt->execute();

                            //assign to variables
                            $comments=$stmt->fetchAll();
                            if(! empty($comments)){
                            foreach($comments as $comment){
                                echo '<div class="comment-box">';
                                    echo '<span class="member-n">' . $comment['Member'] . '</span>';
                                echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                echo '</div>';
                            }
                            }else{
                                echo 'There is no comments to show';
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
<!--        end latest comment-->
    </div>
</div>
    
    
    <?php
//    end dashboard page
	 include $tpl."footer.php";

}else {
	
	header('location: index.php');
	exit();
}

ob_end_flush();
?>
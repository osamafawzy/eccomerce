<?php

session_start();
$pagetitle='Profile';
include "init.php";
if(isset($_SESSION['user'])){
    
    $getUser = $conn->prepare("select * from users where Username=?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
?>
 
<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
               <li>
                   <i class="fa fa-unlock-alt fa-fw"></i>
                   <span> login Name </span>: <?php echo $info['Username']  ?>
               </li>
               <li> 
                   <i class="fa fa-envelope-o fa-fw"></i>
                   <span> Email</span>: <?php echo $info['Email']  ?> 
               </li>
               <li> 
                   <i class="fa fa-user fa-fw"></i>
                   <span> Full Name</span>: <?php echo $info['Fullname']  ?> 
               </li>
               <li> 
                   <i class="fa fa-calendar fa-fw"></i>
                   <span> Register Date</span>: <?php echo $info['Date']  ?>
               </li>
               <li>
                   <i class="fa fa-tags fa-fw"></i>
                   <span> Favourite Category </span>: 
               </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <div class="panel-body">
                    <?php
                    $items = getitems('User_ID',$info['UserID']);
                    if(!empty($items)){
                        echo '<div class="row">';
                    foreach($items as $item){
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag">' . $item['Price'] . '</span>';
                                echo '<img class="img-responsive" src="images.JPG" alt="" >';
                                echo '<div class="caption">';
                                    echo '<h3>'. $item['Name'] .'</h3>';
                                    echo '<p>'. $item['Description'] .'</p>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                      echo '</div>';  
                    }else{
                        echo ' There is no ads to show, create<a href="newad.php"> New AD</a>';
                    }
                    ?>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php
                    $stmt = $conn->prepare("select comment from comments where user_id=?");
                    $stmt->execute(array($info['UserID']));
                    $comments = $stmt->fetchAll();
                if(! empty($comments)){
                    foreach($comments as $comment){
                        echo '<p>'.$comment['comment'] . '</p>';
                    }
                }else{
                    echo 'There is no comment to show';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php

}else{
    header('Location: login.php');
    exit();
}
include $tpl."footer.php";


?>
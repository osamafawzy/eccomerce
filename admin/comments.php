<?php

// =================================================
// == manage comments page 
// == you can edit | delete | approve comments from here
// =================================================

session_start();
$pagetitle = 'Comments';
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


		//select all users except admin
		$stmt=$conn->prepare("SELECT comments.*, items.Name AS Item_Name, users.Username AS Member
        from comments
        INNER JOIN items ON items.Item_ID = comments.item_id
        INNER JOIN users ON users.UserID = comments.user_id
        order by c_id desc
        ");
		$stmt->execute();

		//assign to variables
		$rows=$stmt->fetchAll();


        if (!empty($rows)){

	 ?>

		<h1 class="text-center">Manage Comments</h1>
		<div class="container">
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>#ID</td>
					<td>Comment</td>
					<td>Item Name</td>
					<td>User Name</td>
					<td>Added Date</td>
					<td>Control</td>
				</tr>
				<?php

				foreach($rows as $row){
					echo "<tr>";
						echo "<td>".$row['c_id']."</td>";
						echo "<td>".$row['comment']."</td>";
						echo "<td>".$row['Item_Name']."</td>";
						echo "<td>".$row['Member']."</td>";
						echo "<td>".$row['comment_date']."</td>";
						echo "<td>
								<a href='?do=Edit&comid=".$row['c_id']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
								<a href='?do=Delete&comid=".$row['c_id']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                
                                if ($row['status'] == 0){
                                   echo "<a href='?do=Approve&comid=".$row['c_id']."' class='btn btn-info Activate'><i class='fa fa-check'></i> Approve</a>";
                                }
                                
							echo "</td>";
					
				}

				echo "</tr>";
				?>
				
			</table>
            </div>
</div>
<?php }else{
           echo '<div class="container">';
           echo'<div class="nice-messege">There is on comments to show</div>';
            echo '</div>';
        } ?>
<?php
	}elseif ($do == 'Edit') {	# edit page  
		//check if get request comid is numeric & get the integer value of it
		if ( isset($_GET['comid']) && is_numeric($_GET['comid'])){
			$comid = intval($_GET['comid']);
		}else {
			$comid = 0;
		}
		// select all data depend on this id
		$stmt=$conn->prepare("SELECT * from comments WHERE c_id = ? ");
		//execute query
		$stmt->execute(array($comid));
		//fetch data from database
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		//if there is such id then show the form
		if ($count > 0) { ?>
				<h1 class="text-center">Edit Comments</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="comid" value="<?php echo $comid; ?>">
					<!-- start comment field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Comment</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
							</div>
						</div>
						<!-- end comment field -->	
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
	echo "<h1 class='text-center'>Update Comment</h1>";
	echo "<div class='container'>";
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		//get variables from the form
		$comid = $_POST['comid'];
		$comment = $_POST['comment'];
		

			//update database
		$stmt=$conn->prepare("UPDATE comments SET comment = ? WHERE c_id = ? ");
		$stmt->execute(array($comment, $comid));
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Updated</div>';
		redirecthome($themsg,'back');



		
		
	}else {
         echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";
}elseif ($do == 'Delete') {

	echo "<h1 class='text-center'>Delete Comment</h1>";
	echo "<div class='container'>";

			//check if get request comid is numeric & get the integer value of it
			if ( isset($_GET['comid']) && is_numeric($_GET['comid'])){
				$comid = intval($_GET['comid']);
			}else {
				$comid = 0;
			}
        
            $check = checkitem('c_id','comments' , $comid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("DELETE from comments where c_id= :zid");
				$stmt->bindparam(":zid", $comid);
				$stmt->execute();

				$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Deleted</div>';
                 
		          redirecthome($themsg,'back');
        

			}else{
                    echo "<div class='container'>";
                    $themsg = "<div class='alert alert-danger'>this id not exist</div>";
                    redirecthome($themsg);
                    echo "</div>";
				
			}
		echo "</div>";
}elseif($do == 'Approve') {
        echo "<h1 class='text-center'>Approve Comment</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['comid']) && is_numeric($_GET['comid'])){
				$comid = intval($_GET['comid']);
			}else {
				$comid = 0;
			}
	
            $check = checkitem('c_id','comments' , $comid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("UPDATE comments SET status = 1 where c_id = ?");
				$stmt->execute(array($comid));

				$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Approved</div>';
                 
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


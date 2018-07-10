<?php

session_start();
$pagetitle = 'Items';
if (isset($_SESSION['Username'])){
	include 'init.php';    
    
	// $pagetitle='Dashboard';
	$do = '';
	if (isset($_GET['do'])){
		$do = $_GET['do'];
	}else{
		$do = 'Manage';
	}
    if ($do == 'Manage'){  // Manage Page
 

		$stmt=$conn->prepare("SELECT items.*, categories.Name AS category_name ,users.Username
                              FROM items
                              INNER JOIN categories ON categories.ID = items.Cat_ID
                              INNER JOIN users ON users.UserID =items.User_ID
                              order by Item_ID desc");
		$stmt->execute();

		//assign to variables
		$items=$stmt->fetchAll();
        
        if(! empty($items)){


	 ?>

		<h1 class="text-center">Manage Items</h1>
		<div class="container">
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>#ID</td>
					<td>Name</td>
					<td>Description</td>
					<td>Price</td>
					<td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
					<td>Control</td>
				</tr>
				<?php

				foreach($items as $item){
					echo "<tr>";
						echo "<td>".$item['Item_ID']."</td>";
						echo "<td>".$item['Name']."</td>";
						echo "<td>".$item['Description']."</td>";
						echo "<td>".$item['Price']."</td>";
						echo "<td>".$item['Add_Date']."</td>";
                        echo "<td>".$item['category_name']."</td>";
                        echo "<td>".$item['Username']."</td>";
						echo "<td>
								<a href='?do=Edit&itemid=".$item['Item_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
								<a href='?do=Delete&itemid=".$item['Item_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if ($item['Approve'] == 0){
                                   echo "<a href='?do=Approve&itemid=".$item['Item_ID']."' class='btn btn-info Activate'><i class='fa fa-check'></i> Approve</a>";
                                }
							echo "</td>";
					echo "</tr>";
				}

				
				?>
				
			</table>
		</div>
			<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
		</div>
<?php }else{
           echo '<div class="container">';
           echo'<div class="nice-messege">There is on items to show</div>';
           echo'<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>';
            echo '</div>';
        } ?>
<?php
	}
     elseif ($do == 'Add') { ?>

	   <h1 class="text-center">Add New Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- start name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" placeholder="Name of the item" required="required">
							</div>
						</div>
						<!-- end name field -->
                        
                        <!-- start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" required="required" placeholder="Description of the item">
							</div>
						</div>
						<!-- end Description field -->
                        
                         <!-- start Price field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10">
								<input type="text" name="price" class="form-control" required="required" placeholder="Price of the item">
							</div>
						</div>
						<!-- end Price field -->
                        
                         <!-- start Country of made field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10">
								<input type="text" name="country" class="form-control" required="required" placeholder="country of the item">
							</div>
						</div>
						<!-- end Country of made field -->
                        
                        <!-- start status field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10">
								<select name="status">
                                    <option value="0">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Very Old</option>
                                </select>
							</div>
						</div>
						<!-- end status field -->
                        
                        <!-- start members field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10">
								<select name="member">
                                    <option value="0">...</option>
                                    <?php
                                        $stmt=$conn->prepare("Select * from users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user){
                                            echo "<option value='" .$user['UserID'] . "'>".$user['Username']."</option>";
                                        }
         
                                    ?>
                                </select>
							</div>
						</div>
						<!-- end members field -->
                        
                        <!-- start categories field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10">
								<select name="category">
                                    <option value="0">...</option>
                                    <?php
                                        $stmt2=$conn->prepare("Select * from categories");
                                        $stmt2->execute();
                                        $cats=$stmt2->fetchAll();
                                        foreach($cats as $cat){
                                            echo "<option value='" .$cat['ID'] . "'>".$cat['Name']."</option>";
                                        }
         
                                    ?>
                                </select>
							</div>
						</div>
						<!-- end categories field -->
                        
						
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Item" class="btn btn-success btn-lg">
							</div>
						</div>
						<!-- end submit field -->

					</form>

				</div>

<?php
        
	}elseif ($do=='Insert') {
		//insert items page
	
	if ($_SERVER['REQUEST_METHOD']=='POST'){

		echo "<h1 class='text-center'>Insert Item</h1>";
		echo "<div class='container'>";

		//get variables from the form
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$price = $_POST['price'];
		$country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $cat = $_POST['category'];


		//validate the form

		$formerrors = array();

		if(empty($name)){
			$formerrors[] = 'Name cannot be <strong>Empty</strong>';
		}

		if(empty($desc)){
			$formerrors[] = 'Description cannot be <strong>Empty</strong>';
		}

		if (empty($price)){
			$formerrors[] = 'Price cannot be <strong>Empty</strong>';
		}

		if (empty($country)){
			$formerrors[] = 'Country cannot be <strong>Empty</strong>';
		}

		if ($status == 0){
			$formerrors[] = 'You must choose the <strong>Status</strong>';
		}
        
        if ($member == 0){
			$formerrors[] = 'You must choose the <strong>Member</strong>';
		}
        
        if ($cat == 0){
			$formerrors[] = 'You must choose the <strong>Category</strong>';
		}

		// print all errors
		foreach ($formerrors as $error) {
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}

		//check if there is no errors proceed the update operation
		if(empty($formerrors)){
            
      
                	//Insert into database

			$stmt=$conn->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,User_ID)VALUES(:zname, :zdesc, :zprice, :zcountry,:zstatus, now(),:zcat,:zuser ) ");
			$stmt->execute(array(

				'zname' => $name,
				'zdesc' => $desc,
				'zprice' => $price,
				'zcountry' => $country,
                'zstatus' => $status,
                'zcat' => $cat,
                'zuser' => $member
				));
		
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Inserted</div>';
                redirecthome($themsg,'back');
		
            }
            
        
	}else {
        echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";

	}elseif ($do == 'Edit') {	# edit page  
		//check if get request itemid is numeric & get the integer value of it
		if ( isset($_GET['itemid']) && is_numeric($_GET['itemid'])){
			$itemid = intval($_GET['itemid']);
		}else {
			$itemid = 0;
		}
		// select all data depend on this id
		$stmt=$conn->prepare("SELECT * from items WHERE Item_ID = ?");
		//execute query
		$stmt->execute(array($itemid));
		//fetch data from database
		$item = $stmt->fetch();
		$count = $stmt->rowCount();
		//if there is such id then show the form
		if ($count > 0) { ?>
				<h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
					<!-- start name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" placeholder="Name of the item" required="required" value="<?php echo $item['Name'] ?>">
							</div>
						</div>
						<!-- end name field -->
                        
                        <!-- start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" required="required" placeholder="Description of the item" value="<?php echo $item['Description'] ?>">
							</div>
						</div>
						<!-- end Description field -->
                        
                         <!-- start Price field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10">
								<input type="text" name="price" class="form-control" required="required" placeholder="Price of the item" value="<?php echo $item['Price'] ?>">
							</div>
						</div>
						<!-- end Price field -->
                        
                         <!-- start Country of made field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10">
								<input type="text" name="country" class="form-control" required="required" placeholder="country of the item" value="<?php echo $item['Country_Made'] ?>">
							</div>
						</div>
						<!-- end Country of made field -->
                        
                        <!-- start status field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10">
								<select name="status">
                                    <option value="1"<?php if($item['Status']==1){echo "selected";} ?> >New</option>
                                    <option value="2"<?php if($item['Status']==2){echo "selected";} ?>>Like New</option>
                                    <option value="3"<?php if($item['Status']==3){echo "selected";} ?>>Used</option>
                                    <option value="4"<?php if($item['Status']==4){echo "selected";} ?>>Very Old</option>
                                </select>
							</div>
						</div>
						<!-- end status field -->
                        
                        <!-- start members field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10">
								<select name="member">
                                    <?php
                                        $stmt=$conn->prepare("Select * from users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user){
                                            echo "<option value='" .$user['UserID'] . "'" ;
                                            if ($item['User_ID']==$user['UserID']){echo "selected";}
                                            echo ">".$user['Username']."</option>";
                                        }
         
                                    ?>
                                </select>
							</div>
						</div>
						<!-- end members field -->
                        
                        <!-- start categories field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10">
								<select name="category">
                                    <?php
                                        $stmt2=$conn->prepare("Select * from categories");
                                        $stmt2->execute();
                                        $cats=$stmt2->fetchAll();
                                        foreach($cats as $cat){
                                            echo "<option value='" .$cat['ID'] . "'";
                                            if ($item['Cat_ID']==$cat['ID']){echo "selected";}
                                            echo">".$cat['Name']."</option>";
                                        }
         
                                    ?>
                                </select>
							</div>
						</div>
						<!-- end categories field -->
                        
						
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Update Item" class="btn btn-success btn-lg">
							</div>
						</div>
						<!-- end submit field -->

					</form>
               <?php     
                    	//select all users except admin
		$stmt=$conn->prepare("SELECT comments.*, users.Username AS Member
        from comments
        INNER JOIN users ON users.UserID = comments.user_id
        Where item_id = ?;
        ");
		$stmt->execute(array($itemid));

		//assign to variables
		$rows=$stmt->fetchAll();
        if(!empty($rows)){
	 ?>

		<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
		<div class="table-responsive">
			<table class="main-table text-center table table-bordered">
				<tr>
					<td>Comment</td>
					<td>User Name</td>
					<td>Added Date</td>
					<td>Control</td>
				</tr>
				<?php

				foreach($rows as $row){
					echo "<tr>";
						echo "<td>".$row['comment']."</td>";
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
               <?php     }?>
<?php
	//if there is no such id then show error messege
	} else {
             echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>There is no such ID</div>";
		redirecthome($themsg);
        echo "</div>";
			
		}
}elseif ($do == 'Update') { // update page
	echo "<h1 class='text-center'>Update Item</h1>";
	echo "<div class='container'>";
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		//get variables from the form
		$id = $_POST['itemid'];
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $cat = $_POST['category'];
		
		
		//validate the form

		$formerrors = array();

		if(empty($name)){
			$formerrors[] = 'Name cannot be <strong>Empty</strong>';
		}

		if(empty($desc)){
			$formerrors[] = 'Description cannot be <strong>Empty</strong>';
		}

		if (empty($price)){
			$formerrors[] = 'Price cannot be <strong>Empty</strong>';
		}

		if (empty($country)){
			$formerrors[] = 'Country cannot be <strong>Empty</strong>';
		}

		if ($status == 0){
			$formerrors[] = 'You must choose the <strong>Status</strong>';
		}
        
        if ($member == 0){
			$formerrors[] = 'You must choose the <strong>Member</strong>';
		}
        
        if ($cat == 0){
			$formerrors[] = 'You must choose the <strong>Category</strong>';
		}

		// print all errors
		foreach ($formerrors as $error) {
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}

		//check if there is no errors proceed the update operation
		if(empty($formerrors)){
			//update database
		$stmt=$conn->prepare("update items set Name = ?,Description = ?,Price = ?,Country_Made = ?,Status = ?,Cat_ID = ?,User_ID = ? where Item_ID=?");
		$stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$id));
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Updated</div>';
		redirecthome($themsg,'back');
        }


		
		
	}else {
         echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";
}elseif ($do == 'Delete') {

	echo "<h1 class='text-center'>Delete Item</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['itemid']) && is_numeric($_GET['itemid'])){
				$itemid = intval($_GET['itemid']);
			}else {
				$itemid = 0;
			}
			// select all data depend on this id
			$stmt=$conn->prepare("SELECT * from users WHERE Item_ID = ? limit 1");
        
            $check = checkitem('Item_ID','items' , $itemid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("DELETE from items where Item_ID= :zid");
				$stmt->bindparam(":zid", $itemid);
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
        echo "<h1 class='text-center'>Approve Item</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['itemid']) && is_numeric($_GET['itemid'])){
				$itemid = intval($_GET['itemid']);
			}else {
				$itemid = 0;
			}
        
            $check = checkitem('Item_ID','items' , $itemid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("UPDATE items SET Approve = 1 where Item_ID = ?");
				$stmt->execute(array($itemid));

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
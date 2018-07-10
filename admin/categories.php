<?php

// =================================================
// == manage categories page 
// == you can add | edit | delete members from here
// =================================================

session_start();
$pagetitle = 'Categories';
if (isset($_SESSION['Username'])){
	include 'init.php';    
    
	// $pagetitle='Dashboard';
	

	// start manage page
    
    $do = '';
	if (isset($_GET['do'])){
		$do = $_GET['do'];
	}else{
		$do = 'Manage';
	}


	if ($do == 'Manage'){  // Manage Page
        
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array) ){
            $sort = $_GET['sort'];
        }
        
        
    $stmt=$conn->prepare("SELECT * from categories order by ordering $sort");
		$stmt->execute();

		//assign to variables
		$cats=$stmt->fetchAll(); ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                   <i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering: [
                        <a class="<?php if($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span>Classic</span> ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php 
                        foreach($cats as $cat){
                            echo "<div class='cat'>";
                            echo "<div class='hidden-button'>";
                            echo"<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-primary btn-xs'><i class='fa fa-edit'></i> Edit</a>";
                            echo"<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-danger btn-xs'><i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";
                            echo "<h3>".$cat['Name']."</h3>";
                            echo "<div class='full-view'>";
                                echo "<p>";
                                    if($cat['Description'] == ''){
                                        echo "There is no discription for this category";
                                    }else{
                                        echo $cat['Description'];
                                    }
                                echo "</p>";
                                if($cat['Visiblity'] == 1){
                                echo "<span class='Visiblity'>".'<i class="fa fa-eye"></i> Hidden'."</span>"; 
                                }
                                if($cat['Allow_Comment'] == 1){
                                echo "<span class='Commenting'>".'<i class="fa fa-close"></i> Comment Disable'."</span>"; 
                                }
                                if($cat['Allow_Ads'] == 1){
                                echo "<span class='advertises'>".'<i class="fa fa-close"></i> Ads Disable'."</span>"; 
                                }
                            echo "</div>";
                            echo "</div>";
                            echo "<hr>";
                        }
                    
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
        </div>






<?php
        
}
   elseif ($do == 'Add') { ?>

	   <h1 class="text-center">Add New Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- start name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the category">
							</div>
						</div>
						<!-- end name field -->
						<!-- start Descrition field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Descrition</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" placeholder="Descripe the category">
							</div>
						</div>
						<!-- end Descrition field -->
						<!-- start ordering field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10">
								<input type="text" name="ordering" class="form-control" placeholder="Number to arrange the categories">
							</div>
						</div>
						<!-- end ordering field -->
						<!-- start Visiblity field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visible</label>
							<div class="col-sm-10">
								<div>
                                    <input id="vis-yes" type="radio" name="visiblity" value="0" checked />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visiblity" value="1" />
                                    <label for="vis-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end Visiblity field -->
                        <!-- start commenting field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow commenting</label>
							<div class="col-sm-10">
								<div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" />
                                    <label for="com-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end commenting field -->
                        <!-- start Ads field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10">
								<div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" />
                                    <label for="ads-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end Ads field -->
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Category" class="btn btn-success btn-lg">
							</div>
						</div>
						<!-- end submit field -->

					</form>

				</div>

<?php
	}
   elseif ($do=='Insert') {
		//insert category page
	
	if ($_SERVER['REQUEST_METHOD']=='POST'){

		echo "<h1 class='text-center'>Insert Category</h1>";
		echo "<div class='container'>";

		//get variables from the form
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$order= $_POST['ordering'];
		$visible = $_POST['visiblity'];
        $comment= $_POST['commenting'];
        $ads= $_POST['ads'];
		
            
            //check if Category exist in database
            $check = checkitem("Name","categories",$name);
            if ($check == 1){
                    $themsg = "<div class='alert alert-danger'>sorry this Category is exist</div>";
                    redirecthome($themsg,'back');
                echo '';
            }else {
                	//Insert into database

			$stmt=$conn->prepare("INSERT INTO categories(Name,Description,Ordering,Visiblity,Allow_Comment,Allow_Ads)VALUES(:zname, :zdesc, :zorder, :zvisible,:zcomment, :zads ) ");
			$stmt->execute(array(

				'zname' => $name,
				'zdesc' => $desc,
				'zorder' => $order,
				'zvisible' => $visible,
                'zcomment' => $comment,
                'zads' => $ads

				));
		
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Inserted</div>';
                redirecthome($themsg,'back');
		}

            
        
	}else {
        echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg,'back');
        echo "</div>";
	}
	echo "</div>";

	}
 elseif ($do == 'Edit') {	# edit page  
		//check if get request catid is numeric & get the integer value of it
		if ( isset($_GET['catid']) && is_numeric($_GET['catid'])){
			$catid = intval($_GET['catid']);
		}else {
			$catid = 0;
		}
		// select all data depend on this id
		$stmt=$conn->prepare("SELECT * from categories WHERE ID = ?");
		//execute query
		$stmt->execute(array($catid));
		//fetch data from database
		$cat = $stmt->fetch();
		$count = $stmt->rowCount();
		//if there is such id then show the form
		if ($count > 0) { ?>
            
                <h1 class="text-center">Edit Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>" >
					<!-- start name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" required="required" placeholder="Name of the category" value="<?php echo $cat['Name'] ?>">
							</div>
						</div>
						<!-- end name field -->
						<!-- start Descrition field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Descrition</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" placeholder="Descripe the category" value="<?php echo $cat['Description'] ?>">
							</div>
						</div>
						<!-- end Descrition field -->
						<!-- start ordering field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10">
								<input type="text" name="ordering" class="form-control" placeholder="Number to arrange the categories" value="<?php echo $cat['Ordering'] ?>">
							</div>
						</div>
						<!-- end ordering field -->
						<!-- start Visiblity field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visible</label>
							<div class="col-sm-10">
								<div>
                                    <input id="vis-yes" type="radio" name="visiblity" value="0" <?php if($cat['Visiblity'] == 0){echo 'checked';} ?> />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visiblity" value="1" <?php if($cat['Visiblity'] == 1){echo 'checked';} ?> />
                                    <label for="vis-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end Visiblity field -->
                        <!-- start commenting field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow commenting</label>
							<div class="col-sm-10">
								<div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';} ?> />
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked';} ?> />
                                    <label for="com-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end commenting field -->
                        <!-- start Ads field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10">
								<div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';} ?> />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';} ?> />
                                    <label for="ads-no">No</label>
                                </div>
							</div>
						</div>
						<!-- end Ads field -->
						<!-- start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-success btn-lg">
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
} 
    elseif ($do == 'Update') { // update page
	echo "<h1 class='text-center'>Update Category</h1>";
	echo "<div class='container'>";
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		//get variables from the form
		$id = $_POST['catid'];
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$order = $_POST['ordering'];
        $visible = $_POST['visiblity'];
        $comment = $_POST['commenting'];
        $ads = $_POST['ads'];




			//update database
		$stmt=$conn->prepare("update categories set Name = ?,Description = ?,Ordering = ?,Visiblity = ?,Allow_Comment = ?, Allow_Ads = ? where ID=?");
		$stmt->execute(array($name,$desc,$order,$visible,$comment,$ads,$id));
		$themsg = "<div class='alert alert-success'>" .$stmt->rowCount(). ' Record Updated</div>';
		redirecthome($themsg,'back');



		
		
	}else {
         echo "<div class='container'>";
		$themsg = "<div class='alert alert-danger'>sorry you cannot browse this page directly</div>";
		redirecthome($themsg);
        echo "</div>";
	}
	echo "</div>";
}
  elseif ($do == 'Delete') {

	echo "<h1 class='text-center'>Delete Category</h1>";
	echo "<div class='container'>";

			//check if get request userid is numeric & get the integer value of it
			if ( isset($_GET['catid']) && is_numeric($_GET['catid'])){
				$catid = intval($_GET['catid']);
			}else {
				$catid = 0;
			}
        
            $check = checkitem('ID','categories' , $catid);
		
			//if there is such id then show the form
			if ($check > 0) { 

				$stmt = $conn->prepare("DELETE from categories where ID= :zid");
				$stmt->bindparam(":zid", $catid);
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
}


	 include $tpl."footer.php";

}else {
	
	header('location: index.php');
	exit();
}


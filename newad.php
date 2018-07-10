<?php

session_start();
$pagetitle='Create New AD';
include "init.php";
if(isset($_SESSION['user'])){
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo $_POST['name'];
    }
    
?>
 
<h1 class="text-center">Create New Item</h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New AD</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
					<!-- start name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-9">
								<input type="text" name="name" class="form-control live-name" placeholder="Name of the item" required="required">
							</div>
						</div>
						<!-- end name field -->
                        
                        <!-- start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-9">
								<input type="text" name="description" class="form-control live-desc" required="required" placeholder="Description of the item">
							</div>
						</div>
						<!-- end Description field -->
                        
                         <!-- start Price field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10 col-md-9">
								<input type="text" name="price" class="form-control live-price" required="required" placeholder="Price of the item">
							</div>
						</div>
						<!-- end Price field -->
                        
                         <!-- start Country of made field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10 col-md-9">
								<input type="text" name="country" class="form-control" required="required" placeholder="country of the item">
							</div>
						</div>
						<!-- end Country of made field -->
                        
                        <!-- start status field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10 col-md-9">
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
                        
                        <!-- start categories field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-9">
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
                    <div class="col-md-4">
                         <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$0</span>
                                <img class="img-responsive" src="images.JPG" alt="" >
                                <div class="caption">';
                                    <h3>Title</h3>
                                    <p>Description</p>
                                </div>
                            </div>
                    </div>
                </div>
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
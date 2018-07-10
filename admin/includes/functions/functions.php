<?php 

// title functions that echo the page title in case the page has the variable $pagetitle and echo default title for pther pages

function gettitle(){

	global $pagetitle;

	if (isset($pagetitle)){

		echo $pagetitle;

	}else {
		
		echo 'Default';
	}
}

// redirect function
//...parameters is :
//====error messege = echo the error messege
//====seconds =second before redirect

function redirecthome($themsg, $url = null, $seconds = 3){
    if ($url === null){
        $url = 'index.php';
        $link = 'Home Page';
    }else{
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
          $url = $_SERVER['HTTP_REFERER']; 
            $link = 'previous page';
        }else{
            $url ='index.php';
            $link = 'Home Page';
        }
        
    }
	echo $themsg;
	echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds.</div>";
	header("refresh:$seconds;url=$url");
	exit();
}

// function to check item in database
//..parameters is :
//=====select = item to select (ex: user, item, category )
//==== from =table to select from
//=== value = the value of select
function checkitem($select, $from, $value) {
    global $conn;
    $statement = $conn->prepare("SELECT $select FROM $from WHERE $select =?");
    $statement->execute(array($value));
    $count = $statement->rowcount();
    return $count;
}

//count number of items
//function to count number of items rows
//== items = the item to count
//=== table = the table to count from


function countitems($item, $table){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT($item) from $table");
    $stmt->execute();
    return $stmt->fetchcolumn();
}



//get latest records function
//function to get items from database
//===select = field to select
//===table = the table to choose from
//=== limit = number of records to get




function getlatest($select, $table, $order, $limit = 5){
    global $conn;
    $stmt = $conn->prepare("SELECT $select from $table order by $order DESC limit $limit");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}



























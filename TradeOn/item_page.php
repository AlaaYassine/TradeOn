<?php
require_once 'core/init.php';
$validation = new Validation();
$user = new User();
if(Input::exists()){
    if(Token::check(Input::get('token'))) {
		
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'message' => array(
                'required' => true,
                'min' => 2,
                'max' => 1024,
                
            ),

			));
        if ($validation->passed()) {
           $form = new User();
           $salt = Hash::salt(32);
		   
            try{
                $form->create_comment(array(
                    'message'  => Input::get('message'),
					'uid' => $_SESSION['user'],
					'date'	=>date('Y-m-d H:i:s'),
					'itemid' => $_GET['id']
                    
					
                ));
						Session::flash('success','You have successfully posted a comment.');
						
				//Redirect::to('index.php');
            }catch (Exception $e){
                die($e->getMessage()); // might need to work on redirection
            }
            Session::flash('success', 'You have successfully posted a comment.');
            //header('Location: index.php');
        }/* else { 
         echo '<div id="register-message">';
          foreach($validation->errors() as $error) {
                       echo $error . '<br>';
                 }
 echo '</div>'; 
			
        }*/
    }
}

?>
<!DOCTYPE html>
<html>
<head>

	<title>TradeOn</title>
  <meta charset="utf-8" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="style.css">
<style>
  .message{ color:red;
  font-weight: bold;
  }
</style>
  
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
	 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
	  <?php
if($user->isLoggedIn()){
?>
	   <button type="button" class="navbar-toggle"id="menu-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
<?php
}
?>	 
      <a class="navbar-brand" href="index.php">TradeOn</a>
    </div>
	<div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
      <li><a href="index.php">Home</a></li>
      <li><a href="Mobiles.php">Mobiles</a></li>
      <li><a href="Vehicles.php">Vehicles</a></li>
	  <li><a href="Games.php">Games</a></li>
	  <li><a href="Clothes.php">Clothes</a></li>
	  <li><a href="Accessories.php">Accessories</a></li>
	  <li><a href="Other.php">Other</a></li>
	  <li class="hidden userId" ><?php echo $user->data()->id; ?></li>
    </ul>
	<form class="navbar-form navbar-left" action="search.php" method="POST">
      <div class="form-group">
        <input id="search" name="search" type="text" class="form-control" placeholder="Search...">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
	<button class="form_button" onclick="location.href = 'form.php';" class="myButton" class="float-left submit-button" >Place an Ad</button>
    </div>
  </div>
</nav>

<?php



 
if(Session::exists('success')){
    echo Session::flash('success');
}


if($user->isLoggedIn()){
	$id=$_GET['id']; // retrieves the id from the link and uses it in the below function.
$conn=mysqli_connect("localhost","root","alaa","registration_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}
$currentUser = $user->data()->id;
$result = $conn->query("SELECT * FROM `favs` WHERE `uId` = $currentUser and `itemId` = $id");
$starClass = "fa fa-star-o";
if ($result->num_rows > 0){
	$starClass = "fa fa-star";
}
echo '<div class="main-conatain">
				  <div class="container">';

FormDetails("SELECT `ads`.image,`users`.name,title,detail,Country,city,`ads`.date,username,Interestitem FROM `ads`, `users` WHERE userId = `users`.id and `ads`.id = $id", $starClass);

$form = new items();

$conn=mysqli_connect("localhost","root","alaa","registration_database");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 
	}
$result = $conn->query("SELECT userId FROM `ads` WHERE id = $id");

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$itemUserId = $row['userId'];
	if($user->data()->id != $itemUserId){
	  echo '<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Request a match!</button>';
  }
}
?>
<!-- working on the matching system popup! -->
<! comment form!!!!>
<form class="comment_section" action="" method="post" >
<div class="row bootstrap snippets">
	<div class="col-lg-8 col-md-6 col-sm-10 col-xs-10 pull-left">
        <div class="comment-wrapper">
            <div class="panel panel-info" style=" color:white; border-color:black;">
                <div class="panel-heading" style="background-color:grey; color:white; border-color:black;">
                    Comment panel
                </div>
                <div class="panel-body">
                    <textarea class="form-control" placeholder="write a comment..." rows="3" name="message" id="message" value="<?php echo escape(Input::get('message')); ?>" autocomplete="off"> </textarea>  <span class="message"><?php echo $validation->fieldError('message'); ?></span>
                    <br>
                    <button type="submit" name="submit" class="btn btn-info pull-right"  style="background-color:grey; border-color:b">Post</button>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>

                    <div class="clearfix"></div>
					
					</div>
				</div>
		</div>
	</div>
</div>
</form>
<?php
 $items = $form->comment_posting("SELECT uid, date, message, id, username,image FROM `comments` , `users` WHERE uid = id and itemId = ".$_GET['id']);//the function is in the items.php 
  $requestedUser=$user->data()->username;

  ?>
  
  <!-- Trigger the modal with a button -->
  
<div id="wrapper">
<div id="sidebar-wrapper">

<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
            <li><a href="profile.php?user=<?php echo escape($user->data()->username);?>">profile</a></li>
			<li><a href="favorite.php?user=<?php echo escape($user->data()->username);?>">Favorites</a></li>
			<li><a href="match.php?user=<?php echo escape($user->data()->username);?>">Match</a></li>
			<li><a href="update.php">Update Detail</a></li>
			<li><a href="changePassword.php">Change Password</a></li>
			<li><a href="logout.php">Log Out</a></li>
                
                </ul>
        </nav>
</div>
<div id="page-content-wrapper">
<div class="container-fluid">
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Pick an item to match!</h4>
        </div>
        <div class="modal-body">
         <?php match_items("SELECT `ads`.image as imagead,`ads`.id as ad_Id, title, username, `users`.id as user_Id  FROM `ads`, `users` WHERE username= '$requestedUser' and `users`.id = `ads`.userId");?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>


<?php

?>

</div>
</div>
</div></div></div> <!-- closes container-->
</body>
<footer class="container-fluid text-center" style="position:initial;">
  <h1>About</h1>
 TradeOn is an exchange website used for<br>
 exchanging unwanted items with the ones <br>you
 seek, without paying for anything!

</footer>
<div class="copyright" style="position:initial;">
  <div class="container">
    <div class="col-md-6">
      <p>© 2017 - All Rights with TradeOn</p>
    </div>
    <div class="col-md-6">
      <ul class="bottom_ul">
        <li><a href="index.php">TradeOn.com</a></li>
        <li><a href="contribute.php">Contribute</a></li>
        <li><a href="aboutus.php">About us</a></li>
        <li><a href="contactus.php">Contact us</a></li>
        
      </ul>
    </div>
  </div>
</div>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });


</script>

<script>
	$('.fav').click(function(){
		var itemId = window.location.href.split("=")[1];
		var userId = $('.userId').text();
		$starShape = $('span.fav > i').attr("class");
		$i = $('span.fav > i');
			$.ajax({
				type:'POST',
				url: '/tradeon/addtofavorite.php',
				data: {user: userId, item: itemId},
				datatype: 'text',
				complete: function(response) {
						if($starShape == "fa fa-star"){
							$i.removeClass("fa fa-star");
							$i.addClass("fa fa-star-o");
						} else {
							$i.removeClass("fa fa-star-o");
							$i.addClass("fa fa-star");
						}
					}
		});
		
		
	});
	
</script>




</html>
    <?php
	
    if($user->hasPermission('moderator')){
        echo '<p>You are a moderator.</p>';
    }
}else{
echo '<p><a href="login.php"> Login</a> or <a href="register.php">Register</a> to veiw an ads\'s details</p>';}


?>


<?php
//posting data of each item!!!!
//not finished yet

function FormDetails($sql, $starClass){
$conn=mysqli_connect("localhost","root","alaa","registration_database");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 
	}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
    // output data of each row
	
   
	while($row = $result->fetch_assoc()) {	
				
	 echo '<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12  pull-right">
	 
                <div class="thumbnail">
				<span class="fav" type="button" id="fav" name="fav" style="cursor:pointer; font-size:35px; float: right;" ><i class="'.$starClass.'" aria-hidden="true"></i></span>
                    <img class="img-responsive" src="'.$row["image"].'" alt="" >
					<hr>
                    <div class="caption-full">
                        <h4 class="pull-right">'.$row["date"].'</h4>
						<h4>'.$row["title"].'</h4>
						<h4>Username: '.$row["username"].'</h4> <h4>Name: '.$row["name"].'</h4>
                        <h4>Item for exchange: '.$row["Interestitem"].'<h4>
                        <h4>Location: '.$row["Country"].', '.$row["city"].'<h4>
                        <p>Details: '.$row["detail"].'</p>
                        
                    </div>
					</div>
                    </div>';
	
	}
		
} else {
    echo "<p class='items'>No current Ads to display</p>";

}
$conn->close();
}



function match_items($sql){
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 
	}
			$result = $conn->query($sql);
	
			if ($result->num_rows > 0) {
				
			//	echo "<table class='items'>";
				echo '<div class="main-conatain">
					  <div class="container">';
					  
				// output data of each row
				echo '<form action="" method="post">';
				while($row = $result->fetch_assoc()) {
		echo '
		  <input name="choosingUser" type="hidden" value="'.$row["username"].'">
		  <button type="submit"  name="image_item" class="btn btn-default btn-circle btn-xl" value="'.$row["ad_Id"].'"><img src='.$row["imagead"].' alt="Submit" style="height:60px"></button>
			
			';}
			echo '</form>';
			if(isset($_POST['image_item'])) {
			 $id=$_GET['id']; // id for requested item
			 $result = $conn->query ("SELECT username,`ads`.id,`users`.id,`ads`.userId from `ads`,`users` WHERE `ads`.id=$id and `users`.id = `ads`.userId ");
			 $row = $result->fetch_assoc();
			 $requestedUser=$row['username'];
			 $chosen_item=$_POST['image_item']; //id for chosen own item
			 $choosingUser=$_POST['choosingUser']; 
			 $check=("SELECT requested_item,chosen_item FROM `match` where requested_item='$id' and chosen_item='$chosen_item'");
			 $q = mysqli_query($conn,$check);
			 if(mysqli_num_rows($q) > 0) {
				 echo '<p>Item already matched!</p>';
			 }
			 else {
			$query= ("INSERT INTO `match` (requested_item,chosen_item,requested_user,choosing_user,status) VALUES ('$id','$chosen_item','$requestedUser','$choosingUser','pending')");
			$conn->query($query);
			}}
			} else {
			echo "<p class='comment_display'>No Items created</p>";
			}
			
			
			$conn->close();
		}

			








?>




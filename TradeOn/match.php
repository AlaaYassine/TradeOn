<!DOCTYPE html>
<html>
<head>

	<title>TradeOn</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="style.css">
 <style>
.tab-pane{
    height:400px;
    overflow-y:scroll;
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
	    <button type="button" class="navbar-toggle"id="menu-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.php">TradeOn</a>
    </div>
	<div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="Mobiles.php">Mobiles</a></li>
      <li><a href="Vehicles.php">Vehicles</a></li>
	  <li><a href="Games.php">Games</a></li>
	  <li><a href="Clothes.php">Clothes</a></li>
	  <li><a href="Accessories.php">Accessories</a></li>
	  <li><a href="Other.php">Other</a></li>
	  
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

require_once 'core/init.php';
if(!$username = Input::get('user')){
    Redirect::to('index.php');
}else{
    $user = new User();
    if(!$user->exists()){
        Redirect::to(404);
    }else{
        $data = $user->data();
}}
$user_name=$user->data()->username;

$user = new User();
if($user->isLoggedIn()){
updates_display("SELECT *  FROM `match` WHERE choosing_user = '$user_name' order by id desc limit 10");	
requests_display("SELECT *  FROM `match` WHERE requested_user = '$user_name'  and status='pending'  ");	
    ?>
<div id="wrapper">
<div id="sidebar-wrapper">

<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
            <li><a href="profile.php?user=<?php echo escape($user->data()->username);?>">profile</a></li>
			<li><a href="favorite.php?user=<?php echo escape($user->data()->username);?>">Favorites</a></li>
			<li class="active"><a href="match.php?user=<?php echo escape($user->data()->username);?>">Match</a></li>
			<li><a href="update.php">Update Detail</a></li>
			<li><a href="changePassword.php">Change Password</a></li>
			<li><a href="logout.php">Log Out</a></li>
                
                </ul>
        </nav>
</div>
<div id="page-content-wrapper">
<div class="container-fluid">

    <?php
	
    if($user->hasPermission('moderator')){
        echo '<p>You are a moderator.</p>';
    }
}else{
echo '<p class="sign" ><a href="login.php"> Login</a> or <a href="register.php">Register</a></p>';}



?>
</div>
</div></div>
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
</html>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });

   

</script>


<?php



function requests_display($sql){
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
		//	echo "<table class='items'>";
		
			echo '  
				  <div class="container">
				  
				  <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12  pull-right ">
				   <a class="btn btn-success btn-md pull-right" href="your_match.php?">Your matches</a>
				  <p><strong>Requests</strong></p>
				  
				   <div class="panel panel-default">
				   
				  ';
				  
			// output data of each row

			while($row = $result->fetch_assoc()) {
			$requested_image=("SELECT `ads`.id,image FROM `ads` WHERE `ads`.id=".$row["requested_item"]."");
			// retieving the image of the requested item
			$q = mysqli_query($conn,$requested_image);
			$rimage = mysqli_fetch_assoc($q);
			// retrieving the image of the logged in users items
			$chosen_image=("SELECT `ads`.id,image FROM `ads` WHERE `ads`.id=".$row["chosen_item"]."");
			// retieving the image of the requested item
			$q2 = mysqli_query($conn,$chosen_image);
			$cimage = mysqli_fetch_assoc($q2);
			//<a href="item_page.php?id='.$row["ad_Id"].'">
			
				echo	' <div class="panel-body">
					<div class="row">
					
						<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 text-left">
						<a href="item_page.php?id='.$row["chosen_item"].'""><img src='.$cimage["image"].'  style="height:60px; width:80px;"></a>
								<h4><small><b>'.$row["choosing_user"].'</b></small></h4>
							
						</div>
						<form action="" method="post">
						<div class="col-xs-12 col-sm-5 col-md-3 col-lg-4 text-center">
							 <button class="btn btn-md btn-success" type="submit" name="accept" value='.$row["id"].'>Accept</button>
							 <button class="btn btn-md btn-danger" type="submit" name="decline" value='.$row["id"].'>Decline</button>
						</div></form>
						
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 pull-right">
								
								<img src='.$rimage["image"].'  style="height:60px">
								<h4 class="text-center"><small><b>You</b></small><h4>
							
							
							</div>	
							
						</div>
					</div>
					<hr>';					
		}
		echo '</div>
				</div>
					</div>';	
		if(isset($_POST['accept']) )
			{
			$id = $_POST['accept'];
			
			$query= ("UPDATE `match` SET `status` = 'Accepted' WHERE `id`=$id;");
	
		$conn->query($query);
		}
			else if (isset($_POST['decline'])){
				$id = $_POST['decline'];
			$query= ("UPDATE `match` SET `status` = 'Declined' WHERE `id`=$id; ");
			$conn->query($query);
		} }
		else {
		echo   '<div class="container">
				<div class="col-lg-8 col-md-6 col-sm-6 pull-right col-xs-10">
				<a class="btn btn-success btn-md pull-right" href="your_match.php?">Your matches</a>
				<p><strong>Requests</strong></p>
				<div class="panel panel-default">
				<h4  class="text-center">No current requests.</h4>
				</div></div></div>
		';
		}
		$conn->close();

		
		}
		
		
		
		//updates!
	function updates_display($sql){
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
		echo ' <div class="col-lg-3 col-md-4 col-sm-4 col-xs-10 pull-right">
            <p><strong>updates</strong></p>
            <div class="panel panel-default">
                <div class="panel-body">
				 <div class="tab-pane active" id="test">
                    
                        
                            ';
							
			while($row = $result->fetch_assoc()) {	
			$requested_image=("SELECT `ads`.id,image FROM `ads` WHERE `ads`.id=".$row["requested_item"]."");
			// retieving the image of the requested item
			$q = mysqli_query($conn,$requested_image);
			$rimage = mysqli_fetch_assoc($q);
			// retrieving the image of the logged in users items
			$chosen_image=("SELECT `ads`.id,image FROM `ads` WHERE `ads`.id=".$row["chosen_item"]."");
			// retieving the image of the requested item
			$q2 = mysqli_query($conn,$chosen_image);
			$cimage = mysqli_fetch_assoc($q2);
			
			
			$color = "orange";
			if ($row["status"]=='Accepted'){
				$color = "green";
			}
			else if ($row["status"]=='Declined'){
			$color = "red";}
		 echo '
						
                         <div class="media">
                            	<div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 pull-left">
								<img src='.$rimage["image"].'  style="height:60px; width:70px;">
								<h4><small><b>'.$row["requested_user"].'</b></small></h4>
                           </div>
						   	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
						  <h4  style="color:'.$color.';"><strong>'.$row["status"].'</strong></h4>
						   </div>
                           <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 pull-right text-center">
								<img src='.$cimage["image"].'  style="height:60px; width:70px;">
								<h4 class="text-center"><small><b>You</b></small><h4>
						
							
							</div>	</div>
							<hr>
                        ';
	}
	echo '
                    </div>
                </div>
            </div>
        </div>';
	
	}
	else {
		echo '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-10 pull-right">
            <p><strong>updates</strong></p>
            <div class="panel panel-default">
                <div class="panel-body">
				 <div class="tab-pane active" id="test">
		
		
		<p><strong>no current updates</strong></p>
		
		 </div>
                </div>
            </div></div>'; 
		
		
	}
	
	}

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

    
//$sessionName = Config::get('session/session_name');


/* might be used later for welcoming page
if(Session::exists('success')){
    echo Session::flash('success');
}*/



$user = new User();
$user_name=$user->data()->username;
if($user->isLoggedIn()){
	
accepted_requests("SELECT *  FROM `match` where requested_user = '$user_name' or choosing_user = '$user_name'  and status='Accepted'");	
    ?>
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

    <?php
	
    if($user->hasPermission('moderator')){
        echo '<p>You are a moderator.</p>';
    }
}else{
echo '<p class="sign" ><a href="login.php"> Login</a> or <a href="register.php">Register</a></p>';}



?>
</div>
</div>
</body>
</html>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });

 

</script>
<?php
function accepted_requests($sql){
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
		
		
			echo '  
				  <div class="container">
				 
				  <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				  <p><strong>Matches</strong></p>
				  
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
			
				echo	'<a href="success_match.php?id='.$row["id"].'""> <div class="panel-body">
					<div class="row">
					
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
						<img src='.$cimage["image"].'  style="height:60px; width:80px;">
								<h4><small><b>'.$row["choosing_user"].'</b></small></h4>
							
						</div>
					
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 text-center">
							  <img src=Drawing1-Model2.png  style="height:110px; width:200px;">
							 
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
								
								<img src='.$rimage["image"].'  style="height:60px">
								<h4 class="text-center"><small><b>You</b></small><h4>
							
							
							</div>	
							
						</div>
					</div></a>
					<hr class="style7">';					
		}
		echo '</div>
				</div>
					</div>';
	
}
else {
	echo 'error!';
}


}
?>
<?php
require_once 'core/init.php';
$user = new User();
?>
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
 <style>.row{padding-left:150px;
	 
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
<?php
if(!$user->isLoggedIn()){
?>
<ul class="nav navbar-nav">
		 <li><a href="login.php">Login</a></li>
		<li><a href="register.php">Register</a></li>
</ul>
<?php
}
?>
<button class="form_button" onclick="location.href = 'form.php';" class="myButton" class="float-left submit-button" >Place an Ad</button>
    </div>
  </div>
</nav>
</body>
<?php

		$conn=mysqli_connect("localhost", "root", "alaa", "registration_database");
   
    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_error();
    }
 
    error_reporting(0);
	
	 $output = '';
   if(isset($_POST) && isset($_POST['search'])){
	   $query = $_POST['search'];
	   
        $q = mysqli_query($conn, "SELECT * FROM ads WHERE title LIKE '%$query%'") or die(mysqli_error());
        $c = mysqli_num_rows($q);
        if($c == 0){
            echo '<div class="container">				 
				  <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				   <div class="panel panel-default">
					No search results for <b>"' . $query . '"</b>';
					'</div>
					</div></div>';
        } else {
			echo '<div class="container">				 
				  <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				   <div class="panel panel-default">
				   
				  <h4>searching for: '.$query.'.</h4></br></div>
				  <hr>
				  
				   <div class="panel panel-default">	';	
            while($row = mysqli_fetch_array($q)){
				
					echo	' <div class="panel-body">
			 
					<a href="item_page.php?id='.$row["id"].'">
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4"><img class="img-responsive" src="'.$row["image"].'"style="width: 250px; height:150px;"">
						</div>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4">
							<h4 class="product-name"><strong>'.$row["title"].'</strong></h4>
							<h4><small>'.$row["username"].'</small></h4>
						</div></a>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4 text-right">
							
								<h6><strong>'.$row["date"].'</strong></h6>
							
							</div>	
							
						
					</div>
					<hr  class="style7">';         
            }
			echo '
				</div></div></div>';
        }
}

    mysqli_close($conn);
 

require_once 'core/init.php';
$user = new User();
if($user->isLoggedIn()){
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
}

?>

</div>
</div>
</html>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });

 

</script>
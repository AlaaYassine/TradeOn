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
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="style.css">
 <style>
 .row{
	padding-left:150px;
}

 </style>
  <script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Are you sure?');
}
</script>
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
$requestedUser=$_GET['user'];
$user = new User();
if($user->isLoggedIn()){
	
fav_display("SELECT `ads`.id as ad_Id,`ads`.image as ad_image, title, username,`ads`.date, `users`.id as user_Id  FROM `ads`, `users`, `favs` WHERE username= '$requestedUser' and `users`.id = `favs`.uId and `ads`.id = `favs`.itemId", $user);	
    ?>
<div id="wrapper">
<div id="sidebar-wrapper">

<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
            <li><a href="profile.php?user=<?php echo escape($user->data()->username);?>">profile</a></li>
			<li class="active"><a href="favorite.php?user=<?php echo escape($user->data()->username);?>">Favorites</a></li>
			<li><a href="match.php?user=<?php echo escape($user->data()->username);?>">Match</a></li>
			<li><a href="update.php">Update Detail</a></li>
			<li><a href="changePassword.php">Change Password</a></li>
			<li><a href="logout.php">Log Out</a></li>
                
                </ul>
        </nav>
</div>
</div>


    <?php
	
    if($user->hasPermission('moderator')){
        echo '<p>You are a moderator.</p>';
    }
}else{
echo '<p class="sign" ><a href="login.php"> Login</a> or <a href="register.php">Register</a></p>';}



?>

</body>
</html>


<?php

function fav_display($sql, $user){
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			echo '<div class="main-conatain">
				  <div class="container">
				  <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 pull-right">
				   <div class="panel panel-default">
				  ';
				  
			// output data of each row

			while($row = $result->fetch_assoc()) {
			
				echo	' <div class="panel-body">
					
					<a href="item_page.php?id='.$row["ad_Id"].'">
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4"><img class="img-responsive" src="'.$row["ad_image"].'"style="width: 250px; height:150px;"">
						</div>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4">
							<h4 class="product-name"><strong>'.$row["title"].'</strong></h4><h4><small>'.$row["username"].'</small></h4>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-4 text-right">
							</a>
								<h6><strong>'.$row["date"].'</strong></h6>
							</div>	
							<button  type="button"   data-user="'.$user->data()->id .'" data-item="'.$row["ad_Id"].'" class="btn btn-link btn-xs col-xs-12 col-sm-6 col-md-8 col-lg-4 text-right delete">
								<span class="glyphicon glyphicon-trash"> </span>
							</button>
							
					</div>
					<hr class="style7">';							
		}
		echo '
				</div>
					</div></div>';
		} else {
		echo ' <div class="col-xs-12 col-sm-9 col-md-10 col-lg-12"><p class="items container panel panel-default">
		No current Ads to display
		 </p></div>';
		}
		
		
		$conn->close();

		
		}
?>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });



</script>
	<script>
	$('.delete').click(function(){
		var itemId = $(this).data("item");
		var userId = $(this).data("user");
		
		var del = $(this);
		var rootdiv = del.parent().parent();
		
		$.ajax({
				type:'POST',
				url: '/tradeon/addtofavorite.php',
				data: {user: userId, item: itemId},
				datatype: 'text',
				complete: function(response) {
						del.parent().next().remove();
						del.parent().remove();
						if(rootdiv.children().length == 0){
							location.reload();
						}
						
					}
		});


		
	});
	
</script>	
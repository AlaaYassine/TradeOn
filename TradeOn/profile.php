<html>
<head>
	<title>TradeOn</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

<script>
delete_item = function() {
    $.ajax({
        type:'POST',
        url:'items.php',
        success:function(data) {
            if(data) {
                alert("Are you sure?");
            }
            else {
                alert("ERROR!!!!");
            }
        }
    });
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
    $user = new User($username);
    if(!$user->exists()){
        Redirect::to(404);
    }else{
        $data = $user->data();
    }
    ?>
<div class="container">
<div class="row">
    

        
    <div class="col-sm-12 col-md-12">
        
        <!-- resumt -->
        <div class="panel panel-default">
               <div class="panel-heading resume-heading">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="col-xs-12 col-sm-4">
                           <figure>
                              <img class="img-circle img-responsive" alt="" src="<?php echo escape($data->image);?>" style="height:240; width:250;">
                           </figure>
                           <div class="row">
                              <div class="col-xs-12 social-btns">
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                   
                                  
                                 </div>
                                 
                                
                                 
                                
                              </div>
                              
                              
                           </div>
                        </div>
                        <div class="col-xs-12 col-sm-8">
                           <ul class="list-group">
                              <li class="list-group-item">Username: <?php echo escape($data->username);?></li>
                              <li class="list-group-item">Full Name: <?php echo escape($data->name);?></li>
                              <li class="list-group-item">Age: <?php echo escape($data->age);?></li>
                              <li class="list-group-item"><i class="fa fa-phone"></i> <?php echo escape($data->number);?> </li>
                              <li class="list-group-item"><i class="fa fa-envelope"></i> <?php echo escape($data->email);?></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>Items this user created:</h4>
                  <p>
                   
    <?php
	


}

 $requestedUser=$_GET['user'];
$form = new items(); // function to post the items that are created by the user on the page located in the items.php page
$user = new User();
$user_name=$user->data()->username;
$allowdelete = false;
if ($user_name==$requestedUser && $user->isLoggedIn()){
	$allowdelete = true;
	
} else{
	
}
$items = $form->user_item_display("SELECT `ads`.id as ad_Id, title, username, `users`.id as user_Id  FROM `ads`, `users` WHERE username= '$requestedUser' and `users`.id = `ads`.userId", $allowdelete);
    echo'</p></div>
            </div>
         </div>
		 </div>
		 </div>';


if(Session::exists('success')){
    echo Session::flash('success');
}


if($user->isLoggedIn()){
    ?>
<div id="wrapper">
<div id="sidebar-wrapper">

<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
            <li class="active"><a href="profile.php?user=<?php echo escape($user->data()->username);?>">profile</a></li>
			<li><a href="favorite.php?user=<?php echo escape($user->data()->username);?>">Favorites</a></li>
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


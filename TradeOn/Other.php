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
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

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
	  <li  class="active"><a href="Other.php">Other</a></li>
	  
    </ul>
	<form class="navbar-form navbar-left" action="search.php" method="POST">
      <div class="form-group">
        <input id="search" name="search" type="text" class="form-control" placeholder="Search...">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
<?php
require_once 'core/init.php';
$user = new User();
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
<div class="container">
<h3>Other!</h3>
</div>	
<?php
require_once 'core/init.php';

if(Session::exists('success')){
    echo Session::flash('success');
}

$user = new User();
if($user->isLoggedIn()){
$userId = $user->data()->id;
echo '<label class="hidden userId" >'.$userId.'</label>';
$form = new items(); // function to post the items on the page located in the items.php page
$items = $form->posting("select ads.id as itemId, title, detail, country, City, type, Interestitem, image, ads.date, uId from ads left join favs on ads.id = favs.itemId WHERE type in ('Other') order by itemId desc");    ?>
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
$form = new items(); // function to post the items on the page located in the items.php page
$items = $form->loggedOut_posting("SELECT * FROM `ads` WHERE type in ('other') order by id desc"); }
?>
</div>
</div></div>
</body>
</html>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });
</script>

<script>
	$('.fav').click(function(){
		var itemId = $(this).attr("data-itemid");
		var userId = $('.userId').text();
		//	$starShape = $('span.fav > i').attr("class");
		//$i = $('span.fav > i');
		$i = $(this).find("i");
		$starShape = $i.attr("class");
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
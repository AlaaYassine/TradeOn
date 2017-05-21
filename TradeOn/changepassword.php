<?php
require_once 'core/init.php';
$user = new User();
$validation  = new Validation();
if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}
if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validation  = new Validation();
        $validation = $validation->check($_POST, array(
            'password_current' => array(
                'required'  => true,
                'min'       => 6
            ),
            'password_new' => array(
                'required'  => true,
                'min'       => 6
            ),
            'password_new_again' => array(
                'required'  => true,
                'min'       => 6,
                'matches'   => 'password_new'
            )
        ));
        if($validation->passed()){
            // change password
            if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
                echo 'Your Current Password is in-correct!';
            }else{
                $salt = Hash::salt(32);
                $user->update(array(
                    'password'  => Hash::make(Input::get('password_new'), $salt),
                    'salt'      => $salt
                ));
                Session::flash('success','<div class="container">
	<div class="alert alert-success">
  <strong>Password changed successfully.</strong>
</div></div></div>');
                Redirect::to('index.php');
            }
       }else {}
        
}}
    
?>

<!DOCTYPE html>
<html>
<head>

	<title>TradeOn</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  span{ color:red;
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
</body>
</html>


<?php
require_once 'core/init.php';
if(Session::exists('success')){
    echo Session::flash('success');
}

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
			<li class="active"><a href="changePassword.php">Change Password</a></li>
			<li><a href="logout.php">Log Out</a></li>
                
                </ul>
        </nav>
</div>
</div>

<div class="container">
	<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
<legend class="text-center">Change your password: </legend>

<fieldset>
<form class="form-horizontal" action="" method="post">

    <div class="form-group">
        <label class="col-md-3 control-label" for="password_current">Current Password</label> <span><?php echo $validation->fieldError('password_current'); ?></span>
		<div class="col-md-9">
        <input class="form-control" type="password" name="password_current" id="password_current"/>
    </div></div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="password_new">New Password</label>  <span><?php echo $validation->fieldError('password_new'); ?></span>
		<div class="col-md-9">
        <input class="form-control" type="password" name="password_new" id="password_new"/>
    </div></div>
<br>
    <div class="form-group">
        <label class="col-md-3 control-label" for="password_new_again">New Password again</label>  <span><?php echo $validation->fieldError('password_new_again'); ?></span>
		<div class="col-md-9">
        <input class="form-control" type="password" name="password_new_again" id="password_new_again"/>
    </div></div>
<br>
    <div class="col-md-12 text-right">
		 <input class="btn btn-default btn-lg pull-right" name="submit" type="submit" value="Change" style="background-color:#535b61; color:white;">
		 </div>

    <input type="hidden" name="token" value="<?php echo Token::generate() ;?>"/>
	
</form>
</fieldset>
</div></div></div></div>
    <?php
    if($user->hasPermission('moderator')){
        echo '<p>You are a moderator.</p>';
    }
}else{
echo '<p class="sign" ><a href="login.php"> Login</a> or <a href="register.php">Register</a></p>';}

?>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });


</script>
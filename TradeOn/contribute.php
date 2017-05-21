<?php
require_once 'core/init.php';
$user = new User();
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
                Session::flash('success', 'Password changed successfully.');
                Redirect::to('index.php');
            }
       }else{
                echo '<p id="register-message">Incorrect Username or Password.</p>';
            }
        }else{
            echo '<div id="register-message">';
          foreach($validation->errors() as $error) {
                       echo $error . '<br>';
                 }
 echo '</div>'; 
			
        }
        }
    
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
<div class="container">
	<div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">	  
		<div class="panel panel-default">
   <h1> Help us make this website better! </h1><br>
	<h4> If you think you could help in improving TradeOn or you could help in translating it to different langauges, please do not hesitate and contact us right away. </h4> 
    <br><h4>email: abedb4321@gmail.com<br> alaa_yassine@hotmail.com </h4> 
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
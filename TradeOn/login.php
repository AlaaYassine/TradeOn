<?php
require_once 'core/init.php';
$validation = new Validation();
?>
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

<?php
require_once 'core/init.php';
if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validation = new Validation();
        $validation->check($_POST, array(
            'username'  => array('required' => 'true'),
            'password'  => array('required' => 'true')
        ));
        if($validation->passed()){ //logging user in
            $user = new User();
            $remember = (Input::get('remember') === 'on')? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            if($login){
                Redirect::to('index.php');
            }else{
                echo '<p id="register-message">Incorrect Username or Password.</p>';
            }
        }/*else{
            echo '<div id="register-message">';
          foreach($validation->errors() as $error) {
                       echo $error . '<br>';
                 }
 echo '</div>'; 
			
        }*/
        
    }}


if(Session::exists('success')){
    echo Session::flash('success');
}
?>
<html>
<link rel="stylesheet" type="text/css" href="style.css"/>




<div class="container">
    <div class="row">
		<div class="col-md-5 ">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Please sign in</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form  action="" method="post">
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="Username" name="username" type="text"  autocomplete="off">  <span><?php echo $validation->fieldError('username'); ?></span>
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="password" type="password"  autocomplete="off"> <span><?php echo $validation->fieldError('password'); ?></span>
			    		</div>
			    		<div class="checkbox">
			    	    	<label>
			    	    		<input type="checkbox" name="remember" id="remember"/> Remember me</label> 
			    	    </div>
						<input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>

</html>
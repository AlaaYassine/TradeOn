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
    if(Token::check(Input::get('token'))) {
		
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 3,
                'max' => 50
				
			
            ),
            'age' => array(
                'required' => true,
                'min' => 0,
                'max' => 1000
				
			
            ),
			'email' => array(
                'required' => true,
                'min' => 3,
				'unique' => 'users'),
        ));
        if ($validation->passed()) {
           $form = new User();
           $salt = Hash::salt(32);
		   
            try{
                $form->create(array(
                    'username'  => Input::get('username'),
					'email' =>Input::get('email'),
                    'password'  => Hash::make(Input::get('password'), $salt),
                    'salt'      => $salt,
                    'name'      => Input::get('name'),
					'age'      => Input::get('age'),
					'joined'	=>date('Y-m-d H:i:s'),
					'image'	=> Input::get('image'),
                    'group'     => 1
					
                ));
						Session::flash('success','<div class="alert alert-success">
  <strong>You registered successfully and now can login!</strong>
</div>');
						
				Redirect::to('login.php');
            }catch (Exception $e){
                die($e->getMessage()); // might need to work on redirection
            }
            Session::flash('success', 'You have successfully registered.');
            header('Location: index.php');
        } else { 
         echo '<div id="register-message">';
          foreach($validation->errors() as $error) {
                       echo $error . '<br>';
                 }
 echo '</div>'; 
			
        }
    }
}
?>
	<div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
<legend class="text-center">Register</legend>
<fieldset>
<form class="form-horizontal" class="form_page" action="" method="post">
    <div class="form-group">
	
        <label class="col-md-3 control-label" for="username">Username:</label>
		<div class="col-md-9">
        <input class="form-control" type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"/>
		 
		</div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label" for="password">Password:</label>
		<div class="col-md-9">
        <input class="form-control" type="password" name="password" id="password" value="" autocomplete="off"/>
		</div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="password_again">Retype Password:</label>
		<div class="col-md-9">
        <input class="form-control" type="password" name="password_again" id="password_again" value="" autocomplete="off"/>
		</div>
    </div>
    <div class="form-group">
        <label  class="col-md-3 control-label" for="name">Name:</label>
		<div class="col-md-9">
        <input class="form-control" type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off"/>
    </div>
</div>
 <div class="form-group">
        <label class="col-md-3 control-label" for="age">Age:</label>
		<div class="col-md-9">
        <input class="form-control" type="text" name="age" id="age" value="<?php echo escape(Input::get('age')); ?>" autocomplete="off"/>
    </div>
	</div>


    <div class="form-group">
        <label class="col-md-3 control-label" for="email">Email:</label>
		<div class="col-md-9">
        <input class="form-control" type="email" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off"/>
    </div>
	</div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
	<input type="hidden" name="image" id="image" value="profile_image/default_pic.jpg" autocomplete="off"/>
	<div class="col-md-12 text-right">
	<input class="btn btn-default btn-lg " type="submit" value="Register" style="background-color:#535b61; color:white;"/>
    </div>
</fieldset>
</form>
		</div>
	</div>
</body>
</html>
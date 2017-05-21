<?php
require_once 'core/init.php';
$validation = new Validation();
?>
<html>
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
$user = new User();
if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}
if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validation = new Validation();
        $validation = $validation->check($_POST, array(
            'name'  => array(
                
                'min'      => 2,
                'max'      => 50
            ),
			'age' => array(
			
                'min'      => 1,
                'max'      => 99
			)
        ));
        if($validation->passed()){
            try{
				if ($_FILES['image']['error'] || !is_uploaded_file($_FILES['image']['tmp_name'])) {
						$user->update(array(
							'age'  => Input::get('age'),
							'name'  => Input::get('name'),
							'number'  => Input::get('number')
							
						));
				}
				else
				if ($_POST && !empty($_FILES))
				{
					$formOk = true;

					//Assign Variables
					$path = $_FILES['image']['tmp_name'];
					$name = $_FILES['image']['name'];
					$size = $_FILES['image']['size'];
					$type = $_FILES['image']['type'];

					
					
					$conn=mysqli_connect("localhost","root","alaa","registration_database");
					$user_name=$user->data()->id;
					$sql = "select $user_name as maximum from ads";
					if ($conn->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					 
						}
					$result = $conn->query($sql);
					
					
					$row = $result->fetch_assoc();
					$id = $row['maximum'];
					
					$parts = explode(".", $name);
					$targetFile = "profile_image/".$id.".".$parts[sizeof($parts)-1];
					
					if ($_FILES['image']['error'] || !is_uploaded_file($path)) {
						$formOk = false;
						echo "Error: Error in uploading file. Please try again.";
					}

					//check file extensionf
					if ($formOk && !in_array($type, array('image/png','image/PNG', 'image/x-png', 'image/jpeg','image/JPG', 'image/pjpeg', 'image/gif'))) {
						$formOk = false;
						echo "Error: Unsupported file extension. Supported extensions are JPG / PNG.";
					}
					// check for file size.
					if ($formOk && filesize($path) > 2097152) {
						$formOk = false;
						echo "Error: File size must be less than 4 MB.";
					}

					if ($formOk) {
						// read file contents
						//$targetFile = file_get_contents($path);
						if(move_uploaded_file($path,$targetFile)){
							echo "image uploaded";
						$user->update(array(
						'age'  => Input::get('age'),
						'name'  => Input::get('name'),
						'image'		=> $targetFile,
						'number'  => Input::get('number')
                ));
						}else {
							echo "Error while uploading";
				}}}
                Session::flash('success','<div class="container">
	<div class="alert alert-success">
  <strong>Information Updated Successfully</strong>
</div></div>'); 
                //Redirect::to('index.php');
            }catch (Exception $e){
                die($e->getMessage());
            }
        }else{
            foreach($validation->errors() as $error){
				echo $error, '<br>';
			}
        }
    }
}


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
			<li class="active"><a href="update.php">Update Detail</a></li>
			<li><a href="changePassword.php">Change Password</a></li>
			<li><a href="logout.php">Log Out</a></li>
                
                </ul>
        </nav>
</div>
</div>
<div class="container">
	<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
<legend class="text-center">Update Details: </legend>

<fieldset>
<form class="form-horizontal"  action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-md-3 control-label" for="name">Name</label>
		<div class="col-md-9">
        <input class="form-control" type="text" name="name" id="name" value="<?php echo $user->data()->name;?>"/>
	</div></div>
	
	<div class="form-group">
		  	<label class="col-md-3 control-label">Upload Image</label>
			<div class="col-md-9">
		  	<input type="hidden" name="MAX_FILE_SIZE" value="4194340">
			<input class="form-control" type="file" name="image" id="image" value = "<?php echo $user->data()->image;?>"/>
		    
		  </div></div>
		  
<div class="form-group">
        <label class="col-md-3 control-label" for="age">Phone Number</label>
		<div class="col-md-9">
        <input class="form-control" type="text" name="number" id="number" value="<?php echo $user->data()->number;?>"/>
</div>
       <input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
    </div>
	
	<div class="form-group">
        <label class="col-md-3 control-label" for="age">Age</label> 
		<div class="col-md-9">
        <input class="form-control" type="text" name="age" id="age" value="<?php echo $user->data()->age;?>"/>
</div>
       <input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
    </div>
	<div class="col-md-12 text-right">
	 <input class="btn btn-default btn-lg pull-right" name="submit" type="submit" value="Submit" style="background-color:#535b61; color:white;">
	 </div>
</form>
</fieldset>
</div></div></div></div>
</body>
</html>
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
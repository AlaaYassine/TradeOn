<?php
require_once 'core/init.php';
$user = new User();
if(Input::exists()){
    if(Token::check(Input::get('token'))) {
		$validate = new Validation();
        $validation = $validate->check($_POST, array(
				'message' => array(
                'required' => true,
                'min' => 3,
                'max' => 1024,
                
            ),

			));
        if ($validation->passed()) {
           $form = new User();
           $salt = Hash::salt(32);
		   
            try{
                $form->double_comment(array(
                    'message'  => Input::get('message'),
					'user_mId' => $_SESSION['user'],
					'date'	=>date('Y-m-d H:i'),
					'matchId' => $_GET['id']
                    
					
                ));
						Session::flash('success','You have successfully posted a comment.');
						
				//Redirect::to('index.php');
            }catch (Exception $e){
                die($e->getMessage()); // might need to work on redirection
            }
            Session::flash('success', 'You have successfully posted a comment.');
            //header('Location: index.php');
        } else { 
         echo '<div id="register-message">';
          foreach($validation->errors() as $error) {
                       echo $error . '<br>';
                 }
 echo '</div>'; 
			
        }
    }
}



//  WHERE id = $id
?>
<!DOCTYPE html>
<html>
<head>

	<title>TradeOn</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
      <li><a href="index.php">Home</a></li>
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



 
if(Session::exists('success')){
    echo Session::flash('success');
}
$username=$user->data()->username;
$id=$_GET['id'];
$conn=mysqli_connect("localhost","root","alaa","registration_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}
$result = $conn->query("SELECT id,choosing_user,requested_user FROM `match` WHERE id = $id and (choosing_user='$username' or requested_user='$username') and status='Accepted'");

if (!$result->num_rows > 0)
	 Redirect::to('index.php');
	else{
    


		$id=$_GET['id']; 
		double_display("SELECT * from `match` where id=$id");
		?>
		
		
		
<! comment form!!!!>
<form class="comment_section" action="" method="post" >
<div class="row bootstrap snippets">
	<div class="col-md-6 col-md-offset-2 col-sm-12">
        <div class="comment-wrapper">
            <div class="panel panel-info" style=" color:white; border-color:black;">
                <div class="panel-heading" style="background-color:grey; color:white; border-color:black;">
                    Comment panel
                </div>
                <div class="panel-body">
                    <textarea class="form-control" placeholder="write a comment..." rows="3" name="message" id="message" value="<?php echo escape(Input::get('message')); ?>" autocomplete="off"> </textarea>
                    <br>
                    <button type="submit" name="submit" class="btn btn-info pull-right"  style="background-color:grey; border-color:b">Post</button>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>

                    <div class="clearfix"></div>
					
					</div>
				</div>
		</div>
	</div>
</div>
</form>
	
<?php	


private_comments("SELECT user_mid, date, message, `users`.id, username,image FROM `private_comments` , `users` WHERE user_mid = `users`.id and matchId = ".$_GET['id']);//the function is in the items.php		
	
		}
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
</body>
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

<?php		
		
		
		
		function double_display($sql){ //displays the data of both items
	$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
		//	echo "<table class='items'>";
		
			echo '  
				  <div class="container">
				  
				  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				  <p><strong>Exchange</strong></p>
				  
				   <div class="panel panel-default">
				   
				  ';
				  
			// output data of each row

			while($row = $result->fetch_assoc()) {
			// retieving the data of the logged in user
			$requested_data=("SELECT `ads`.id,image,date,title,name,Interestitem,detail FROM `ads` WHERE `ads`.id=".$row["requested_item"]."");
			
			$q = mysqli_query($conn,$requested_data);
			$r_data = mysqli_fetch_assoc($q);
			// retrieving the data of the other user
			$logged_data=("SELECT `ads`.id,image,date,title,name,Interestitem,detail FROM `ads` WHERE `ads`.id=".$row["chosen_item"]."");
			
			$q2 = mysqli_query($conn,$logged_data);
			$l_data = mysqli_fetch_assoc($q2);
			
				echo	' <div class="panel-body">
					<div class="row">
					
						<div class="col-xs-12 col-sm-8 col-md-4 col-lg-3 text-left">
						<h4>Your Item: </h4>
						<img src='.$r_data["image"].'  style="height:300px; width:90%;">
						<h4>'.$r_data["title"].'</h4>
						<h4>Username: '.$row["requested_user"].'</h4> <h4>Name: '.$r_data["name"].'</h4>
                        <h4>Item for exchange: '.$r_data["Interestitem"].'<h4>
                        
                        <p>Details: '.$r_data["detail"].'</p>
							
						</div>
						
						<div class="col-xs-1-hidden col-sm-1 col-md-1 col-lg-1 text-center">
							<img src=Drawing1-Model2.png  style="height:300px; width:250px; position:absolute;top:40px;">
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 pull-right">
								<h4>Traders Item: </h4>
								<img src='.$l_data["image"].'  style="height:300px; width:90%;">
								<h4>'.$l_data["title"].'</h4>
								<h4>Username: '.$row["choosing_user"].'</h4> <h4>Name: '.$l_data["name"].'</h4>
								<h4>Item for exchange: '.$l_data["Interestitem"].'<h4>

								<p>Details: '.$l_data["detail"].'</p>
							
							
							</div>	
							
						</div>
					</div>
					<hr>';					

		}
		echo '</div>
				</div>
					</div>';
		
		$conn->close();

		
		}}
		
		
		
		
		function private_comments($sql){ //posts the private comment

		$conn=mysqli_connect("localhost","root","alaa","registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			
			echo '<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
		   
			<div class="panel panel-default">
				<div class="panel-body">';
			// output data of each row

			while($row = $result->fetch_assoc()) {
			echo '<ul class="media-list">
						<li class="media">
							<a href="profile.php?user='.$row["username"].'" class="pull-left">
								<img src='.$row["image"].' alt="" class="img-circle" style="height:70px; width:70px;">
							</a>
							<div class="media-body">
								<span class="text-muted pull-right">
									<small class="text-muted">'.$row["date"].'</small>
								</span>
								<strong class="text-success"><a href="profile.php?user='.$row["username"].'">'.$row["username"].'</a></strong>
								<p>
									'.$row["message"]. '
								</p>
							</div>
						</li><hr>';
		}
		echo '</div></div></div></div>';
		} else {
		echo '
		<div class="container">
			<div class="col-lg-12 col-md-4 col-sm-12 col-xs-12 pull-right">
		   
			<div class="panel panel-default">
				<div class="panel-body">
		<h4 class="media">No Comments.</h4>
		
		</div></div></div></div>
		';
		}
		$conn->close();
		}
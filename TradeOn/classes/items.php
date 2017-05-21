<html>
<link rel="stylesheet" type="text/css" href="style.css"/>
	<style type="text/css">

	</style>
</html>

<?php
//this function is to retrieve the information of the image and title from the database and post them on the website to be viewed 

class items {
	public function __construct(){
        $this->_db = DB::getInstance();
								}
	public function posting($sql){
	//public function posting($sql, $page, $itemsperpage){
	require_once 'core/init.php';
	$user = new User();
	$host = $this->_sessionName = Config::get('mysql/host');
	$username = $this->_sessionName = Config::get('mysql/username');
	$password = $this->_sessionName = Config::get('mysql/password');
	$db = $this->_sessionName = Config::get('mysql/db');
	$conn=mysqli_connect($host, $username, $password, $db);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

			}
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		//	echo "<table class='items'>";
			echo '<div class="main-conatain">
				  <div class="container">
				
				  ';
				  
			// output data of each row
			//$from = $itemsperpage*$page + 1;
			//$to = $itemsperpage*($page+1);
			//$index = 1;
			$userfavs;
			$currentUser = $user->data()->id;
			while($row = $result->fetch_assoc()){
				$userId_row = $row['uId'];
				if($row['uId'] == $currentUser){
					$userfavs[$row['itemId']] = 1;
				}
				else{
					$userfavs[$row['itemId']] = 0;
				}
			}
			
			mysqli_data_seek($result, 0);
			while($row = $result->fetch_assoc()) {
				$starClass = "fa fa-star-o";
				if($userfavs[$row['itemId']] == 1){
					$starClass = "fa fa-star";
				}
				// limiting the size of the interestitem to display
				$x=$row["Interestitem"];
				$z=strlen($x);
				if($z>10){
					$x=substr($x,0,10);
					$y=$x.'...';
					
				}else {
					$y=$x;
				
				}
			if($userfavs[$row['itemId']] != 2){
				echo	'<div class="col-ms-12 col-ms-10 col-md-4 col-lg-4">
				
							<div class="project">
								<figure class="img-responsive">
									<img src="'.$row["image"].'"style="width: 250px; height:250px;"">
										<figcaption>
											 <span class="project-details">'.$row["title"].'</span>
											 <span class="fav" type="button" id="fav" name="fav" data-itemid="'.$row["itemId"].'" style="cursor:pointer; font-size:35px; float:right;" ><i class="'.$starClass.'" style="color: orange;" aria-hidden="true"></i></span>
												<span class="project-creator">'.$row["country"].','.$row["City"].'</span>
													<span class="project-interested">Interested in: <b>'.$y.'</b></span>
													</figcaption>
               <a href="item_page.php?id='.$row["itemId"].'""> <span class="actions">
                        <button class="btn btn-warning bnt-action" type="submit" >View </button>
                </span></a>
            </figure>
        </div>
    </div>';
	$userfavs[$row['itemId']] = 2;
			}
	
		}
		echo '
				</div>
					</div>';
		} else {
		echo '
		<div class="container">
				 
				  <div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
				   <div class="panel panel-default">
		<p class="items">No current Ads to display</p>
		</div></div></div>';
		}
		$conn->close();
}


public function loggedOut_posting($sql){

		$conn=mysqli_connect("localhost", "root", "alaa", "registration_database");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

			}
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		//	echo "<table class='items'>";
			echo '<div class="main-conatain">
				  <div class="container">';
				  
			// output data of each row

			while($row = $result->fetch_assoc()) {
				// limiting the size of the interestitem to display
				$x=$row["Interestitem"];
				$z=strlen($x);
				if($z>10){
					$x=substr($x,0,10);
					$y=$x.'...';
					
				}else {
					$y=$x;
				
				}
				echo	'<div class="col-ms-10 col-md-6 col-lg-4">
							<div class="project">
								<figure class="img-responsive">
									<img src="'.$row["image"].'"style="width: 250px; height:250px;"">
										<figcaption>
											 <span class="project-details">'.$row["title"].'</span>
												<span class="project-creator">'.$row["country"].','.$row["City"].'</span>
													<span class="project-interested">Interested in: <b>'.$y.'</b></span>
													</figcaption>
               <a href="item_page.php?id='.$row["id"].'""> <span class="actions">
                        <button class="btn btn-warning bnt-action" type="submit" >View </button>
                </span></a>
            </figure>
        </div>
    </div>';
											
					
		}
		echo '
				</div>
					</div>';
		//echo "</table>";
		} else {
		echo "<p class='items'>No current Ads to display</p>";
		}
		$conn->close();
}




// displaying the comments
public function comment_posting($sql){

	$host = $this->_sessionName = Config::get('mysql/host');
	$username = $this->_sessionName = Config::get('mysql/username');
	$password = $this->_sessionName = Config::get('mysql/password');
	$db = $this->_sessionName = Config::get('mysql/db');
	$conn=mysqli_connect($host, $username, $password, $db);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

			}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			
			echo '<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           
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

			//echo '<tr><td>'.$row["username"].':	</td><td>'.$row["message"].'</td><td>'.$row["date"].'</td></tr>';
		}
		echo '</div></div></div></div>';
		} else {
		echo '
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		   
			<div class="panel panel-default">
				<div class="panel-body">
		<h4 class="media">No Comments.</h4>
		
		</div></div></div></div>
		';
		}
		$conn->close();
}
//function to present the items that the user created on their profile

public function user_item_display($sql, $canDelete){
	$host = $this->_sessionName = Config::get('mysql/host');
	$username = $this->_sessionName = Config::get('mysql/username');
	$password = $this->_sessionName = Config::get('mysql/password');
	$db = $this->_sessionName = Config::get('mysql/db');
	$conn=mysqli_connect($host, $username, $password, $db);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

		}
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			
			echo '<table><form class="container" action="" method="post">';
			// output data of each row

			while($row = $result->fetch_assoc()) {
			echo '<tr><td> <a href="item_page.php?id='.$row["ad_Id"].'"">'.$row["title"].'</a>';
			if($canDelete){
			echo '<button type="submit" name="deleteItem"  value='.$row["ad_Id"].' class="btn btn-link btn-xs">
			<span class="glyphicon glyphicon-trash"> </span>
			</button>';
			}
			echo '</td></tr>
			
			
			';
			//<input type="submit" name="deleteItem"  value="delete" />
			//<a class="close" type="submit" name="deleteItem" >&times;</a></td></tr>
			//echo '<tr><td>'.$row["username"].':	</td><td>'.$row["message"].'</td><td>'.$row["date"].'</td></tr>';
		}
	

		echo '</table></form>';
		
		if(isset($_POST['deleteItem']) )
		{
			$id = $_POST['deleteItem'];
			
			$query= ("DELETE FROM `ads` WHERE id = $id");
		$conn->query($query);
		}
		} else {
		echo "<p class='comment_display'>No Items created</p>";
		}
		// testing delete button!!!!!!!!!
		
		$conn->close();
}}




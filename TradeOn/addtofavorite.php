<?php
if(isset($_POST['user']) &&  isset($_POST['item'])){
	$user = $_POST['user'];
	$item = $_POST['item'];
		$conn=mysqli_connect("localhost","root","alaa","registration_database");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);

			}
			require_once 'core/init.php';
			
			$check=("SELECT * FROM `favs` WHERE uId = '$user' and itemId ='$item'");
			$q = mysqli_query($conn,$check);
			
			
			if(mysqli_num_rows($q) > 0) {
				$query= ("delete FROM `favs` WHERE uId = '$user' and itemId ='$item'");
				$conn->query($query);
				exit("success");
			}else{
			$query= ("INSERT INTO favs (uId, itemId) VALUES ($user,$item); ");
			$conn->query($query);
				exit("success");}
} else {
	exit("error");

}
		 
?>
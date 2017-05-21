
<?php
class Image_upload {
	public function __construct(){
        $this->_db = DB::getInstance();
    }
	

public function imageupload(){
if ($_POST && !empty($_FILES)) {
	$formOk = true;

	//Assign Variables
	$path = $_FILES['image']['tmp_name'];
	$name = $_FILES['image']['name'];
	$size = $_FILES['image']['size'];
	$type = $_FILES['image']['type'];

	if ($_FILES['image']['error'] || !is_uploaded_file($path)) {
		$formOk = false;
		echo "Error: Error in uploading file. Please try again.";
	}

	//check file extension
	if ($formOk && !in_array($type, array('image/png', 'image/x-png', 'image/jpeg', 'image/pjpeg', 'image/gif'))) {
		$formOk = false;
		echo "Error: Unsupported file extension. Supported extensions are JPG / PNG.";
	}
	// check for file size.
	if ($formOk && filesize($path) > 500000) {
		$formOk = false;
		echo "Error: File size must be less than 500 KB.";
	}

	if ($formOk) {
		// read file contents
		$content = file_get_contents($path);

		//connect to mysql database
		if ($conn = mysqli_connect('localhost', 'root', 'alaa', 'registration_database')) {
			$content = mysqli_real_escape_string($conn, $content);
			$sql = "insert into ads (image) values ('{$name}')";

			if (mysqli_query($conn, $sql)) {
				$uploadOk = true;
				$imageId = mysqli_insert_id($conn);
			} else {
				echo "Error: Could not save the data to mysql database. Please try again.";
			}

			mysqli_close($conn);
		} else {
			echo "Error: Could not connect to mysql database. Please try again.";
		}
	}
}}}





 /*require_once 'core/init.php';
if(isset($_FILES['file'])){
	$file=$_FILES['file'];
	
	//file properties
	$file_name= $file['name'];
	$file_tmp= $file['tmp_name'];
	$file_size= $file['size'];
	$file_error= $file['error'];
	
	// file extension
	$file_ext=explode('.',$file_name=);
	$file_ext=strtolower(end($file_ext));
	
	$allowed=array('txt','jpg');
	if(in_array($file_ext, $allowed)){
		if($file_error===0){
			if($file_size<=2097152){
				$file_name_new=uniqid('',true) . '.' . $file_ext;
				$file_destination='uploads/' . $file_name_new;
				
				if(move_uploaded_file($file_tmp,$file_name_new)
					
			}
		}
		
	}
		
	
}*/


<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	if(isset($_POST['submit'])) {
		if(isset($_GET['go'])) {
			
			//Time for some validation
			//Mobile, Email, Name cannot be null. 
			$error = 0;
			if (empty($_POST['name'])) {
				$authnamebad = "Your name is required";
				$error = 1;
			} else { 
				$authnamebad = "";
				$authname = test_input($_POST['name']);
				$error = 0;
				if (!preg_match("/^[a-zA-Z ]*$/",$authname)) {
				  $authnamebad = "Please enter in real words and letters."; 
				  $error = 1;
				}
			}
			if (empty($_POST['mobile'])) {
				$authmobilebad = "Your mobile number is required.";
				$error = 1;
			} else { 
				$authmobilebad = "";
				$error = 0;
				$authnumber = $_POST['mobile'];
				if (!is_numeric($authnumber)) {
					$authmobilebad = "Please only enter numbers";
					$error = 1;
				}
			}
			if (empty($_POST['email'])) {
				$authemailbad = "Your email address is required.";
				$error = 1;
			} else { 
				$authemailbad = "";
				$error = 0;
				$authemail = test_input($_POST['email']);
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				  $authemailbad = "Enter a valid email."; 
				  $error = 1;  
				}
			}
			if (!empty($_POST['locationy'])) {
				$longitude = $_POST['locationy'];
				if (!is_numeric($longitude)) {
						$locationbad = "Please only enter numbers";
						$error = 1;
				}
			}
			if (!empty($_POST['locationx'])) {
				$latitude = $_POST['locationx'];
				if (!is_numeric($latitude)) {
						$locationbad = "Please only enter numbers";
						$error = 1;
				}
			}
			$comment = $_POST['comment'];
			if (!preg_match("/^[a-zA-Z0-9 ]*$/",$comment)) {
				  $commentbad = "Please enter in real words and letters."; 
				  $error = 1;
			}
			
			//Start of checking for empty requirements 
			if ($error == 0) {

			//Get all the variables
				$plantname = $_POST['commonname']; $datetime = date("Y-m-d H:i:s"); $dafor = $_POST['rarity'];
			
			//Search for plant using the common name then assign it to the record. 
			//$plantidfind = "SELECT Common_Name FROM Plant_Table WHERE Common_Name LIKE '$plantname'";
				$data = "SELECT * FROM Plant_Table WHERE Common_Name LIKE '$plantname'";
				$query = $dbconnect->query($data);
				$result = $query->fetch_assoc();
				$plantid = $result['Plant_ID'];		
			
			//Find if database already has this contact
			//If it has the contact then assign that contact's ID to the record and don't change the contact database	
			//If the contact is not in the database, add them and assign that contact's ID to the record
				$data = "SELECT * FROM Contacts_Table WHERE Email_Address LIKE '$authemail'";
				$query = $dbconnect->query($data);
				$row = $query->num_rows;
				if ($row == 0) {
					$newcontact = "INSERT INTO Contacts_Table (Mobile_Number, Email_Address, Name) VALUES ('$authnumber', '$authemail', '$authname')";
					$dbconnect->query($newcontact);
				}
				$query = $dbconnect->query($data);
				$result = $query->fetch_assoc();
				$contactid = $result['Contact_ID'];	
				
			//Add the specimen image (Done separately from scene for validation purposes)
			//Check if a file has been uploaded
			if(isset($_FILES['species_image']) && $_FILES['species_image']['size'] > 0){
				$specimen_image_dir = "../media/specimen/";
				$specimen = $specimen_image_dir . basename($_FILES["species_image"]["name"]);
				$checkUpload = 1;
				$specimen_imageType = pathinfo($specimen,PATHINFO_EXTENSION);
				
				//Check if image is correct file type
				$checkType = getimagesize($_FILES["species_image"]["tmp_name"]);
				if($checkType !== false) {
					$checkUpload = 1;
				} else {
					$checkUpload = 0;
				}		
				
				//Check if the image is too large
				if ($_FILES["species_image"]["size"] > 1000000) {
					$checkUpload = 0;
				}
				
				// Limit the types of files that can be used.
				if($specimen_imageType != "jpg" && $specimen_imageType != "png" && $specimen_imageType != "jpeg" && $specimen_imageType != "gif" ) {
					$checkUpload = 0;
				}
				
				//Upload file if all the previous checks are fine
				if ($checkUpload == 1) {
					move_uploaded_file($_FILES["species_image"]["tmp_name"], $specimen);
				}
			//End of check
			}
			
			//Add the specimen image (Done separately from scene for validation purposes)
			//Check if a file has been uploaded
			if(isset($_FILES['scene_image']) && $_FILES['scene_image']['size'] > 0){
				$scene_image_dir = "../media/scene/";
				$scene = $scene_image_dir . basename($_FILES["scene_image"]["name"]);
				$checkUpload = 1;
				$scene_imageType = pathinfo($scene,PATHINFO_EXTENSION);
				
				//Check if image is correct file type
				$checkType = getimagesize($_FILES["scene_image"]["tmp_name"]);
				if($checkType !== false) {
					$checkUpload = 1;
				} else {
					$checkUpload = 0;
				}		
				
				//Check if the image is too large
				if ($_FILES["scene_image"]["size"] > 1000000) {
					$checkUpload = 0;
				}
				
				// Limit the types of files that can be used.
				if($scene_imageType != "jpg" && $scene_imageType != "png" && $scene_imageType != "jpeg" && $scene_imageType != "gif" ) {
					$checkUpload = 0;
				}
				
				//Upload file if all the previous checks are fine
				if ($checkUpload == 1) {
					move_uploaded_file($_FILES["scene_image"]["tmp_name"], $scene);
				}
			//End of check
			}
			
			//Add the new record into the database
				$newrec = "INSERT INTO Plant_Record_Table (Plant_ID, Contact_ID, Datetime, Abundance, Text, Specimen, Scene, Latitude, Longitude) VALUES ('$plantid', '$contactid', '$datetime',  '$dafor', '$comment', '$specimen', '$scene', '$latitude', '$longitude')";
				$dbconnect->query($newrec);
			
			//Add the record to the event log table.
				$data = "SELECT * FROM Plant_Record_Table ORDER BY Record_ID DESC LIMIT 1";
				$query = $dbconnect->query($data);
				$result = $query->fetch_assoc();
				$recordid = $result['Record_ID'];	
				$newevent = "INSERT INTO Event_Log_Table (Plant_ID, Contact_ID, Record_ID, Datetime, Modify) VALUES ('$plantid', '$contactid', '$recordid', '$datetime', 'Create')";
				$dbconnect->query($newevent);
			
			//End of error check
			}
		//End of isset get
		}
	//End of isset post
	}
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
                	<h3> Create a new record </h3>
                    <p> Fill in the form below to create a new record </p>
                    <p> Open the drop-down menu and type to search for a plant </p>
                    
                    <form  method="post" action="create.php?go"  id="searchrecord" enctype="multipart/form-data"> 
                        Plant Name: <br> 
                        <select name="commonname">
                        <?
							$data = "SELECT Common_Name FROM Plant_Table ORDER BY Common_Name";	
							$selection = $dbconnect->query($data);
							if ($selection->num_rows > 0) {
								while($row = $selection->fetch_assoc()) {
									if ($row["Common_Name"] != NULL){
										echo '<option value="' . $row["Common_Name"] . '">' . $row["Common_Name"] . '</option>';
									}
								}
							}
						?>
                   		</select> <br><br>
                        
                        Location (Latitude/Longitude): <br> <input type="text" name="locationx"> <input type="text" name="locationy"><span class="error"><?php echo $locationbad;?></span><br><br>
                        Rarity (DAFOR): <br> <select name="rarity"> <option value="dominant">Dominant</option> <option value="abundant">Abundant</option> <option value="frequent">Frequent</option> <option value="occasional">Occasional</option> <option value="rare">Rare</option> </select> <br><br>
                        Image (of species): <br> <input type="file" name="species_image"> <br><br>
                        Image (of scene): <br> <input type="file" name="scene_image"> <br>
                        Only JPG, JPEG, PNG & GIF files are allowed. Maximum file size is 1000kb. <br><br>
                        Comment (Specify site location): <br> <input type="text" name="comment"> <span class="error"><?php echo $commentbad;?></span> <br><br><br>
                       
                        
                    <h3> Your Information </h3>
                        Your name: <br> <input type="text" name="name"><span class="error">* <?php echo $authnamebad;?></span> <br><br> 
                        Your phone number: <br> <input type="text" name="mobile"><span class="error">* <?php echo $authmobilebad;?></span> <br><br>
                        Your E-mail: <br> <input type="text" name="email"><span class="error">* <?php echo $authemailbad;?></span> <br><br><br>
                    <input  type="submit" name="submit" value="Submit"> 
               		</form> 
                    
				</div>
            </div>

<?php	
	$dbconnect->close();
	include("../inc/footer.html");
?>
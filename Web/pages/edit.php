<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
	
	if(isset($_GET['id'])) {
		//Find the record data
			$data = "SELECT * FROM Plant_Record_Table WHERE Record_ID LIKE '" . $_GET['id'] . "'";	
			$selection = $dbconnect->query($data);
			$recorddata = $selection->fetch_assoc();
			
			//Find species data
			$plantid = $recorddata["Plant_ID"];
			$data = "SELECT * FROM Plant_Table WHERE Plant_ID LIKE '$plantid'";
			$query = $dbconnect->query($data); 
			$plantdata = $query->fetch_assoc();
			
			//Find contact data
			$contactid = $recorddata["Contact_ID"];
			$data = "SELECT * FROM Contacts_Table WHERE Contact_ID LIKE '$contactid'";
			$query = $dbconnect->query($data); 
			$contactdata = $query->fetch_assoc();
	}	
	if(isset($_POST['submit'])) {
		
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
			if (!empty($_POST['comment'])) {
				if (!preg_match("/^[a-zA-Z0-9 ]*$/",$comment)) {
					  $commentbad = "Please enter in real words and letters."; 
					  $error = 1;
				}
			}
			
			//Start of checking for empty requirements 
			

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

			//Update record
			$editrec = "UPDATE Plant_Record_Table SET Plant_ID='$plantid', Contact_ID='$contactid', Datetime='$datetime', Abundance='$dafor', Text='$comment', Specimen='$specimen', Scene='$scene', Latitude='$latitude', Longitude='$longitude' WHERE Record_ID=" . $_GET['id'];
			//$dbconnect->query($editrec);

			

	}
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
                <div class="section" style="width:50%">
                    <h3> Create a new record </h3>
                        <p> Fill in the form below to create a new record </p>
                        <p> Open the drop-down menu and type to search for a plant </p>
                        
						<? echo '<form  method="post" action="edit.php?id=' . $_GET['id'] . '"  id="searchrecord">'; ?>
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
                        Location (Latitude/Longitude): <br> <input type="text" name="locationx"> <input type="text" name="locationy"><span class="error"><?php echo $locationbad;?></span> <br><br>
                        Rarity (DAFOR): <br> <select name="rarity"> <option value="dominant">Dominant</option> <option value="abundant">Abundant</option> <option value="frequent">Frequent</option> <option value="occasional">Occasional</option> <option value="rare">Rare</option> </select> <br><br>
                        Image (of species): <br> <input type="file" name="species_image"> <br><br>
                        Image (of scene): <br> <input type="file" name="scene_image"> <br>
                        Only JPG, JPEG, PNG & GIF files are allowed. Maximum file size is 1000kb. <br><br>
                        Comment (Specify site location): <br> <input type="text" name="comment"><span class="error"><?php echo $commentbad;?></span> <br><br><br>
                        
                    <h3> Your Information </h3> <br>
                        Your name: <br> <input type="text" name="name"><span class="error">* <?php echo $authnamebad;?></span> <br>
                        Your phone number: <br> <input type="text" name="mobile"><span class="error">* <?php echo $authmobilebad;?></span <br><br>
                        Your E-mail: <br> <input type="email" name="email"><span class="error">* <?php echo $authemailbad;?></span> <br><br>
                    <input  type="submit" name="submit" value="Submit"> <br><br>
               		</form> 
                    
                    <?
					if(isset($_POST['submit'])) {
						if ($dbconnect->query($editrec) === TRUE) {
							echo "<b>Record Status:</b> Record updated successfully";
						} else {
							echo "Error: " . $editrec . "<br>" . $dbconnect->error;
						}
					} else {
						echo "<b>Record Status:</b> Currently editing";
					}
					?>
                    
                  </div>
                <div class="section" style="width:45%; height: 719px">
<?php
						                           
			//Print all the record data
				if (empty($recorddata['Specimen'])) {
					echo '<img width="200px" height="200px" alt="image_placeholder" src="../media/web_graphics/species_placeholder.jpg">';   
				} else {
					echo '<img width="200px" height="200px" alt="image_placeholder" src="'. $recorddata['Specimen'] . '">';   
				}   
				echo '<h3> <b>Plant name:</b>  ' . $plantdata["Common_Name"] . '</h3>';
				echo '<p> <b>Species name:</b>  ' . $plantdata["Species"] . '</p>';
				echo '<p> <b>Record description:</b>  ' . $recorddata["Text"] . '</p>';
				echo '<p> <b>Record location:</b>  ' . $recorddata["Latitude"] . ',' . $recorddata["Longitude"] . '</p>';
				echo '<p> <b>Record date/time:</b>  ' . $recorddata["Datetime"] . '</p>';
			
			//Print all the author data
				echo '<h3> <b>Author information</b></h3>';
				echo '<p> <b>Author name:</b> ' . $contactdata["Name"] . '</p>';
				echo '<p> <b>Author E-Mail:</b> ' . $contactdata["Email_Address"] . '</p>';
				echo '<p> <b>Author phone number:</b> ' . $contactdata["Mobile_Number"] . '</p>';
?>                
		</div>                    
	</div>
</div>

<?php	

	$dbconnect->close();
	include("../inc/footer.html");
?>
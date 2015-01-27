<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
<?php
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
					
					//Print all the record data
					if (empty($recorddata['Specimen'])) {
                		echo '<img width="200px" height="200px" alt="image_placeholder" src="../media/web_graphics/species_placeholder.jpg">';   
					} else {
						echo '<img width="200px" height="200px" alt="image_placeholder" src="'. $recorddata['Specimen'] . '">';   
					}
                        
					echo '<div class="section" style="height: 178px; width: 77.3%"';
                    	echo '<h3> <b>Plant name:</b>  ' . $plantdata["Common_Name"] . '</h3>';
						echo '&nbsp;&nbsp;&nbsp;<b>Species name:</b>  ' . $plantdata["Species"] ;
                        echo '<p> <b>Record description:</b>  ' . $recorddata["Text"] . '</p>';
                        echo '<p> <b>Record location:</b>  ' . $recorddata["Latitude"] . ',' . $recorddata["Longitude"] . '</p>';
                        echo '<p> <b>Record date/time:</b>  ' . $recorddata["Datetime"] . '</p>';
						echo '<p> <b>Abundance:</b>  ' . $recorddata["Abundance"] . '</p>';

                   	echo '</div><br>';
					
					//Print all the author data
					echo '<div class="section" style="height: 298px; width: 55%" align="center">';
						echo '<h3> <b>Author information</b></h3>';
						echo '<p> <b>Author name:</b> ' . $contactdata["Name"] . '</p>';
						echo '<p> <b>Author E-Mail:</b> ' . $contactdata["Email_Address"] . '</p>';
						echo '<p> <b>Author phone number:</b> ' . $contactdata["Mobile_Number"] . '</p>';
						echo '<a href=""><div class="section" style="height: 20x; width: 100px" align="center"> <a href="../pages/create.php"><h6> Create Record</h6></div></a>';
						echo '<a href=""><div class="section" style="height: 20x; width: 100px" align="center"> <a href="../pages/edit.php?id=' . $_GET['id'] . '"><h6> Edit Record</h6></div></a>'; 
						echo '<a href=""><div class="section" style="height: 20x; width: 100px" align="center"> <a href="../pages/delete.php?id=' . $_GET['id'] . '"><h6> Delete Record</h6></div></a>';
					echo '</div>';
					if (empty($recorddata['Scene'])) {
                		echo '<img src="http://maps.google.com/maps/api/staticmap?center='. $recorddata["Latitude"] . ',' . $recorddata["Longitude"] .'&zoom=8&size=400x300&sensor=false" style="width: 420px; height: 320px;" />'  ;
					} else {
						echo '<img width="420px" height="320px" alt="image_placeholder" src="'. $recorddata['Scene'] . '">';   
					}
					
					echo '</div></div>';
				}
				
	$dbconnect->close();
	include("../inc/footer.html");
?>
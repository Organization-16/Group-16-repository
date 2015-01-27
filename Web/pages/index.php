<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
	
	//Find the record data
		$data = "SELECT * FROM Plant_Record_Table ORDER BY Record_ID DESC LIMIT 1";	
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
		
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
            	<? if (empty($recorddata['Specimen'])) {
                    echo '<img width="200px" height="200px" alt="image_placeholder" src="../media/web_graphics/species_placeholder.jpg">';   
                } else {
                    echo '<img width="200px" height="200px" alt="image_placeholder" src="'. $recorddata['Specimen'] . '">';   
                }    ?>  
                        
						<div class="section" style="height: 178px; width: 77.3%">             
                        	<p> The most recent addition to the record database is: </p>
                            <ul> <?
                                echo '<li> <b>Plant name:</b> &nbsp;&nbsp; ' . $plantdata["Common_Name"] . '</li>';
                                echo '<li> <b>Species name:</b> &nbsp;&nbsp; ' . $plantdata["Species"] . '</li>';
                                echo '<li> <b>Record description:</b> &nbsp;&nbsp; ' . $recorddata["Text"] . '</li>';
                                echo '<li> <b>Author name:</b> &nbsp;&nbsp;' . $contactdata["Name"] . '</li>';
								echo '<li> <b>Author E-Mail:</b> &nbsp;&nbsp;' . $contactdata["Email_Address"] . '</li>';
                            ?> </ul>
                        </div>
                        
                        <br>
                        
                        <div class="section" style="height: 300px; width: 75.1%"> 
                        	<h2> Welcome! </h2>
                            <p> This website is designed for the CS22120 group project assignment </p>
                            <p> On this website you will be able to browse, create, edit and delete the records for plant species. </p>
                            <p> This is the web side of the project; it directly comminicates to a database that holds all the data. This database is also connected to the android application side of the project. </p>
                            <p> Above you will find a series of links that will help you edit or view the records currently stored in the database. You are also able to create new records via a form on the create page of the web site. The latest addition to the database will be shown in the boxes above and a log of the most recently added records will be provided in the box to the right of this one. </p>
                        </div>
                        
                        <div class="section" style="height: 300px; width: 200px"> 
                        	<table width="200px">
                            <?
								//Find the record data
									$data = "SELECT * FROM Plant_Record_Table ORDER BY Record_ID DESC LIMIT 14";	
									$selection = $dbconnect->query($data);
									
									if ($selection->num_rows > 0) {
										while($recorddata = $selection->fetch_assoc()) {
											echo '<tr><td style="border-right: 1px solid black"> <a href="../pages/view.php?id=' . $recorddata["Record_ID"] . '">&nbsp; <b>View</b> &nbsp;</a></td><td>&nbsp;' . $recorddata["Datetime"] . '&nbsp;</td></tr>';
										}
									}
							?>
                            </table>
                        </div>
            </div>
		</div>
            
<?php	
	$dbconnect->close();
	include("../inc/footer.html");
?>
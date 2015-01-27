<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
	
	if(isset($_POST['submit'])) {
		if(isset($_GET['go'])) {
			//Get search parameters
			$search = $_POST['search'];
			$error = 0;
			if (!preg_match("/^[a-zA-Z0-9 ]*$/",$search)) {
				$error = 1;
				$searchbad = "Please enter only numbers and letters";
			}
			//Get the required information from the database
			if ($error == 1) {
				$data = "SELECT * FROM Plant_Record_Table";	
			} else {
				$data = "SELECT * FROM Plant_Table WHERE Common_Name LIKE '%" . $search . "%'";
				$query = $dbconnect->query($data);
				$searchdata = $query->fetch_assoc();
				$data = "SELECT * FROM Plant_Record_Table WHERE Plant_ID LIKE '%" . $searchdata['Plant_ID'] . "%'";
			}
		}
	} else {
		//Get the required information from the database
		$data = "SELECT * FROM Plant_Record_Table";	
	}
?>

	<!-- Content -->
    <div class="content">
    	<div class="content-inner">  
			<div class="section" align="center" style="width: 98%"> 
    			<h3> Record browsing </h3>
				<p> Select a record to display from the table below. </p>
                <p> Search by plant name. </p>
                <form  method="post" action="browse.php?go"  id="searchrecord"> 
                    <input  type="text" name="search"> 
                    <input  type="submit" name="submit" value="Search Records">  <span class="error"><?php echo $searchbad;?></span>
                </form> 
                <br>
                <table style="width: 800px; text-align:center">
                            	<tr>
                                	<th> Record # </th>
                                    <th> Plant name </th>
                                    <th> Species </th>
                                    <th> Location </th>
                                    <th> Date Taken </th>
                                    <th> View </th>
                                    <th> Edit </th>
                                    <th> Delete </th>
                                </tr>
<?php
		
		//Find each record and print them row by row
		$selection = $dbconnect->query($data);
		if ($selection->num_rows > 0) {
			while($recorddata = $selection->fetch_assoc()) {
				
				//Find species data
					$plantid = $recorddata["Plant_ID"];
					$data = "SELECT * FROM Plant_Table WHERE Plant_ID LIKE '$plantid'";
					$query = $dbconnect->query($data); 
					$plantdata = $query->fetch_assoc();
				
				//Print the table of records from the database
					echo '<tr><td>' . $recorddata["Record_ID"] . '</td><td>' . $plantdata["Common_Name"] . '</td><td>' . $plantdata["Species"] . '</td><td>' . $recorddata["Latitude"] . ','  . $recorddata["Longitude"] . '</td><td>' . $recorddata["Datetime"] . '</td><td> <a href="../pages/view.php?id=' . $recorddata["Record_ID"] . '"> View </a></td> <td> <a href="../pages/edit.php?id=' . $recorddata["Record_ID"] . '"> Edit </a></td> <td> <a href="../pages/delete.php?id=' . $recorddata["Record_ID"] . '"> Delete </a></td></tr>';
				
			}
		}
?>
				</table>
				<br><br>
			</div>      	
		</div>
	</div>

<?php	
	$dbconnect->close();
	include("../inc/footer.html");
?>
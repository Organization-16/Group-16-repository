<?php
	include("../inc/headerdelete.html");	
	include("../inc/navigation.html");
	include("../inc/connect.php");
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
                
<?php
				if(isset($_GET['id'])) {
    				$delete = "DELETE FROM Plant_Record_Table WHERE Record_ID =" . $_GET['id'];
					
					if($dbconnect->query($delete) === TRUE) {
						echo "Record data has been deleted";
					} else {
						echo "Unable to delete record, error: " . $dbconnect->error;
					}
			    }
?>
				</div>
            </div>

<?php	
	$dbconnect->close();
	include("../inc/footer.html");
?>
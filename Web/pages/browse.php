<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
					<div class="section" align="center" style="width: 98%"> 
                    	<h3> Record browsing </h3>
                        <p> Select a record to display from the table below. </p>
                        <table style="width: 500px">
                            	<tr>
                                	<th> Record name </th>
                                    <th> Location </th>
                                    <th> Date Taken </th>
                                    <th> View </th>
                                    <th> Edit </th>
                                    <th> Delete </th>
                                </tr>
                                <tr>
                                	<td> First record </td>
                                    <td> First location </td>
                                    <td> First date </td>
                                    <td> <a href="../pages/view.php"> View </a></td>
                                    <td> <a href="../pages/edit.php"> Edit </a></td>
                                    <td> <a href="../pages/delete.php"> Delete </a></td>
                                </tr>
                                <tr>
                                	<td> Second record </td>
                                    <td> Second location </td>
                                    <td> Second date </td>
                                    <td> <a href="../pages/view.php"> View </a></td>
                                    <td> <a href="../pages/edit.php"> Edit </a></td>
                                    <td> <a href="../pages/delete.php"> Delete </a></td>
                                </tr>
                        </table>
                        <br><br>
                    </div>
                	
				</div>
            </div>

<?php	
	include("../inc/footer.html");
?>
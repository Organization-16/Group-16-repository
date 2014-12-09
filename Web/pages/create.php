<?php
	include("../inc/header.html");	
	include("../inc/navigation.html");
?>

	<!-- Content -->
            <div class="content">
            	<div class="content-inner">  
                	<h3> Create a new record </h3>
                    <p> Fill in the form below to create a new record </p>
                    
                    <form>
                    <fieldset>
                    <legend style="font-weight:bold">Record Information</legend>
                    	Species Name: <br> <input type="text" name="species_name"> <br><br> 
                        Location (GPS): <br> <input type="number" name="gps_x"> <input type="number" name="gps_y"> <br><br> 
                        Rarity (DAFOR): <br> <select name="dafor"> <option value="dominant">Dominant</option> <option value="abundant">Abundant</option> <option value="frequent">Frequent</option> <option value="occasional">Occasional</option> <option value="rare">Rare</option> </select> <br><br> 
                        Image (of species): <br> <input type="file" name="species_image"> <br><br> 
                        Comment: <br> <textarea name="comment"></textarea><br><br><br> 
                    
                    <legend style="font-weight:bold">Your Information</legend>  <br> 
                        Your name: <br> <input type="text" name="author_name"> <br><br> 
                        Your phone number: <br> <input type="text" name="author_phone"> <br><br> 
                        Your E-mail: <br> <input type="email" name="author_email"> <br><br><br> 
                        
                        <input type="button" value="Submit" name="submit">
                        
                    </fieldset>
                    </form>
                    
				</div>
            </div>

<?php	
	include("../inc/footer.html");
?>
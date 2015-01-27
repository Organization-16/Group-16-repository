<?php
        include("../inc/header.html");
        include("../inc/navigation.html");
		
		$dbserver = "db.dcs.aber.ac.uk";
		$user = "thw17";
		$pw = "unseen1";
		$dbname = "thw17";
		 
		//Connect to the database
		$dbconnect = new mysqli($dbserver, $user, $pw, $dbname);
		//Check if connected to the database
		if ($dbconnect->connect_error) {
				die("Connection failed: " . $dbconnect->connect_error);
				}
			   
		//prepare values and bind
		$stmt = $conn->prepare("INSERT INTO PlantInfo (commonname, species) VALUES (?, ?)");
		$stmt->bind_param("ss", $commonname, $species); //arguments are string, string
		 
		//PlantRecord is the plant record table, unsure what the column names are
		$stmt = $conn->prepare("INSERT INTO PlantRecord (Plant, Date, Time, Location, Text) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("siiss", $plantname, $date, $time, $locationx, $text); //arguments are string, double, double, string, string, blob(large unsigned data, eg binary info for a pic)
		 
		//User information
		$stmt = $conn->prepare("INSERT INTO Contacts (name, mobilenumber, emailaddress) VALUES (?, ?, ?)");
		$stmt->bind_param("sisb", $name, $mobilenumber, $emailaddress, $picture); //string, int, string
		 
		//Set the parameters (get this information from user input(???) ($_GET[""] ?))
		 
		//Plantinfo table, common name and species
		$commonname = $_POST["commonname"];
		$species = $_POST["species"];
		 
		//Plantrecord table, location (needs changing in the db to two seperate float values), rarity (needs adding to db), text(comment), picture
		$locationx = $_POST["locationx"];
		$locationy = $_POST["Locationy"];
		$rarity = $_POST["rarity"];
		/* $picture = "species_image"; find out how to insert picture into table */
		$text = $_POST["text"];
		 
		//Contacts (user) information, name, number, email address
		$name = $_POST["name"]; //db is just one field for name, firstname and lastname would be better
		$mobilenumber = $_POST["mobilenumber"];
		$emailaddress = $_POST["emailaddress"];
		 
		echo "New record successfully added";
		 
		$stmt->close();
		$dbconnect->close();
?>
 
        <!-- Content -->
            <div class="content">
                <div class="content-inner">  
                        <h3> Create a new record </h3>
                    <p> Fill in the form below to create a new record </p>
                   
                    <form>
                    <fieldset>
                    <legend style="font-weight:bold">Record Information</legend>
                    Common Name: <br> <input type="text" name="commonname"> <br><br>
                    Species Name: <br> <input type="text" name="species"> <br><br>
                    Location (GPS): <br> <input type="number" name="locationx"> <input type="number" name="locationy"> <br><br>
                    Rarity (DAFOR): <br> <select name="rarity"> <option value="dominant">Dominant</option> <option value="abundant">Abundant</option> <option value="frequent">Frequent</option> <option value="occasional">Occasional</option> <option value="rare">Rare</option> </select> <br><br>
                    Image (of species): <br> <input type="file" name="species_image"> <br><br>
                    Comment: <br> <textarea name="text"></textarea><br><br><br>
                                               
                   
                   <legend style="font-weight:bold">Your Information</legend>  <br>
                        Your name: <br> <input type="text" name="name"> <br><br>
                        Your phone number: <br> <input type="text" name="mobilenumber"> <br><br>
                        Your E-mail: <br> <input type="email" name="emailaddress"> <br><br><br>
                       
                   <input type="button" value="Submit" name="submit">
                       
                   </fieldset>
                   </form>
                   
                                </div>
            </div>
 
<?php  
        include("../inc/footer.html");
?>
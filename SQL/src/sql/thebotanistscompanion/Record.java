package sql.thebotanistscompanion;

import java.io.Serializable;
import java.util.Date;

public class Record implements Serializable {
	
	private static final long serialVersionUID = 1854578625797496677L;
	

	
	
	// Variables
		private int ID;
		private String comment;
		private String DAFOR;
		private Date editdate = new Date();
		private double latitude;
		private double longitude;
		private String PlantCommon;
		private String PlantLatin;
		private String sceneIMGPath;
		private String SpecimenIMGPath;
		private boolean uploaded;




		


public Record() {
}

// Constructors
public Record(String comment, String PlantLatin, String PlantCommon,
		String dAFOR, double latitude, double longitude,
		String SpecimenIMGPath, String sceneIMGPath, int ID) {
	super();
	this.ID = ID;
	this.comment = comment;
	this.PlantLatin = PlantLatin;
	this.PlantCommon = PlantCommon;
	DAFOR = dAFOR;
	this.latitude = latitude;
	this.longitude = longitude;
	this.SpecimenIMGPath = SpecimenIMGPath;
	this.sceneIMGPath = sceneIMGPath;
}

public int getID() {
	return ID;
}

public void setID(int id) {
	this.ID = id;
}

public String getComment() {
	return comment;
}

public String getDAFOR() {
	return DAFOR;
}

// Setters and Getters
public Date getEditdate() {
	return editdate;
}

public double getLatitude() {
	return latitude;
}

public double getLongitude() {
	return longitude;
}

public String getPlantCommon() {
	return PlantCommon;
}

public String getPlantLatin() {
	return PlantLatin;
}

public String getSceneIMGPath() {
	return sceneIMGPath;
}

public String getSpecimenIMGPath() {
	return SpecimenIMGPath;
}

public boolean isUploaded() {
	return uploaded;
}

public void setComment(String comment) {
	this.comment = comment;
}

public void setDAFOR(String dAFOR) {
	DAFOR = dAFOR;
}

public void setEditdate(Date editdate) {
	this.editdate = editdate;
}

public void setLatitude(double latitude) {
	this.latitude = latitude;
}

public void setLongitude(double longitude) {
	this.longitude = longitude;
}

public void setPlantCommon(String PlantCommon) {
	this.PlantCommon = PlantCommon;
}

public void setPlantLatin(String PlantLatin) {
	this.PlantLatin = PlantLatin;
}

public void setSceneIMGPath(String sceneIMGPath) {
	this.sceneIMGPath = sceneIMGPath;
}

public void setSpecimenIMGPath(String SpecimenIMGPath) {
	this.SpecimenIMGPath = SpecimenIMGPath;
}

public void setUploaded(boolean uploaded) {
	this.uploaded = uploaded;
}
}
	


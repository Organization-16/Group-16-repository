package sql.thebotanistscompanion;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;



public class ManageSQL extends SQLiteOpenHelper {
	
	//db version
	private static final int database_Version=1;
	// db name
	private static final String database_NAME ="RecordDB";
	private static final String table_RECORDS="records";
	private static final String record_ID ="id";
	private static final String record_title="title";
	private static final String record_plant_name_common="common plant name";
	private static final String record_plant_name_latin="latin plant name";
	private static final String record_DAFOR="dafor";
	private static final String record_latitude="latitude";
	private static final String record_longitude="longitude";
	private static final String record_comment="comment";
	private static final String record_scene_image_path="location image path";
	private static final String record_specimen_image_path="specimen image path";
	
	private static final String[] COLUMNS ={record_ID,record_plant_name_common,record_plant_name_latin,record_DAFOR,record_latitude,record_longitude,record_comment,record_scene_image_path,record_specimen_image_path};
	
	public ManageSQL(Context context) {
		super(context, database_NAME,null,database_Version);
	}

	@Override
	public void onCreate(SQLiteDatabase db) {
		// Create record table
		String CREATE_RECORD_TABLE = "CREATE TABLE records ( "+"id INTEGER PRIMARY KEY AUTOINCREMENT," + "common plant name TEXT"+ "latin plant name TEXT" + "DAFOR TEXT"+ "GPS COORDS TEXT" +" location image IMAGE"+ "specimen image IMAGE)";
		db.execSQL(CREATE_RECORD_TABLE);
	}

	@Override
	public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
		
		//Drop records table if one already exists
		db.execSQL("DROP TABLE IF EXISTS records");
		this.onCreate(db);
		
		
	}
	
	public void createRecord (Record record) {
		// Get reference of RecordDB in writable format
		SQLiteDatabase db= this.getWritableDatabase();
		
		// Create the values that are going to be inserted into the database
		ContentValues cv = new ContentValues();
		cv.put(record_ID, record.getID());
		cv.put(record_plant_name_common, record.getPlantCommon());
		cv.put(record_plant_name_latin,record.getPlantLatin());
		cv.put(record_DAFOR,record.getDAFOR());
		cv.put(record_latitude, record.getLatitude());
		cv.put(record_longitude,record.getLongitude());
		cv.put(record_comment, record.getComment());
		cv.put(record_scene_image_path, record.getSceneIMGPath());
		cv.put(record_specimen_image_path, record.getSpecimenIMGPath());
		
		//insert record
		db.insert(table_RECORDS, null, cv);
		
		//close database 
		db.close();
	}
	
	public Record readRecord(int id){
		// get reference of RecordDB in readable format
		SQLiteDatabase db= this.getReadableDatabase();
		
		//get record query
		Cursor curse =db.query(table_RECORDS,// table
				COLUMNS,"id = ?",new String[] {String.valueOf(id)},null,null,null,null);
		
		
		// if results aren't null, parse
		if(curse!=null)
			curse.moveToFirst();
		
		Record record =new Record();
		record.setID(Integer.parseInt(curse.getString(0)));
		record.setPlantCommon(curse.getString(1));
		record.setPlantLatin(curse.getString(2));
		record.setDAFOR(curse.getString(3));
		record.setLatitude(curse.getDouble(4));
		record.setLongitude(curse.getDouble(5));
		record.setSceneIMGPath(curse.getString(6));
		record.setSpecimenIMGPath(curse.getString(7));
		
		return record;
	}
		
		public int updateRecord(Record record){
			//get reference of RecordDB in writable format
			SQLiteDatabase db= this.getWritableDatabase();
			
			//Create the values to be inserted into the database
			ContentValues cv = new ContentValues();
			cv.put(record_ID, record.getID());
			cv.put(record_plant_name_common, record.getPlantCommon());
			cv.put(record_plant_name_latin,record.getPlantLatin());
			cv.put(record_DAFOR,record.getDAFOR());
			cv.put(record_latitude, record.getLatitude());
			cv.put(record_longitude,record.getLongitude());
			cv.put(record_comment, record.getComment());
			cv.put(record_scene_image_path, record.getSceneIMGPath());
			cv.put(record_specimen_image_path, record.getSpecimenIMGPath());
			
			//update the values in the record
			int n= db.update(table_RECORDS, cv, record_ID +"=?",new String[] { String.valueOf(record.getID())});
			
			db.close();
			return n;
		}
		
		public void DeleteRecord(Record record){
			
			//get reference of the RecordDB in writable format
			SQLiteDatabase db= this.getWritableDatabase();
			
			//Delete the record
			db.delete(table_RECORDS,record_ID+"= ?", new String[] {String.valueOf(record.getID()) });
			db.close();
			
		}
		
		
	
	
}

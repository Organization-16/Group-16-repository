package sql.thebotanistscompanion;



	import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

	import sql.thebotanistscompanion.DatabaseSignature.DatabaseTable;
import android.content.ContentValues;
import android.content.Context;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteException;
import android.database.sqlite.SQLiteOpenHelper;

	public class ManageSQLDbHelper extends SQLiteOpenHelper {
		//Database Version
		private static final int DATABASE_VERSION =1;
		//Database Name
		private static final String DATABASE_NAME = "RecordDB";
		private static final String DATABASE_PATH ="/data/data/MY_PACKAGE/databases/";// needs to be decided
		private static final String TEXT_TYPE =" TEXT";
		private static final String COMMA = ",";
		
		private static final String CREATE_ENTRY_TO_TABLE =
				"CREATE TABLE" + DatabaseTable.TABLE_NAME +"PRIMARY KEY,"+ "(" +
		DatabaseTable.COLUMN_NAME_ENTRY_ID + TEXT_TYPE + COMMA+
		DatabaseTable.COLUMN_NAME_TITLE + TEXT_TYPE + COMMA+
		
		
		//... Other options
		")";
		
		private static final String DELETE_RECORDS =
				"GET RID OF TABLE IF THERE IS ONE" + DatabaseTable.TABLE_NAME;
		private final Context context;
		private SQLiteDatabase db;
		
		/**
		 * Constructor
		 * 
		 * Takes and keeps reference of the passed context in order to gain access to the applications assets
		 * @param context
		 */
		public ManageSQLDbHelper(Context context){
			super(context,DATABASE_NAME,null,DATABASE_VERSION);
			
			this.context = context;
				
			}

		@Override
		public void onCreate(SQLiteDatabase db) {
			
			db.execSQL(CREATE_ENTRY_TO_TABLE);
		}

		@Override
		public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
			db.execSQL(DELETE_RECORDS);
			onCreate(db);
			
		}
	/**
	 * Creates a, empty database on the system and then will rewrite this database with data from your own
	 * @throws IOException
	 * @throws SQLException
	 */
	public void createDatabase() throws IOException, SQLException{
		
		boolean dbExist = checkDatabase();
		
		if(dbExist){
			// Database already exists so perform no action
		}else{
			this.getReadableDatabase();
			//By calling this method an empty database will be created into the system path.
			
			try{
				
				copyDatabase();
				
			}catch (IOException e){
				throw new Error("Unable to copy database");
			}
		}
	}

		/**
		 * Check if the database exists to avoid copying again every time the application is opened	
		 * @return true if it exists  and false if it doesn't
		 * 
		 *
		 */

	private boolean checkDatabase(){
		
		SQLiteDatabase check = null;
		
		try{
			String path= DATABASE_PATH + DATABASE_NAME;
			check = SQLiteDatabase.openDatabase(path, null,SQLiteDatabase.OPEN_READONLY);
			
		}catch (SQLiteException e){
			// database is yet to exist
		}
		
		if(check!=null){
			check.close();
		}
		
		return check != null ? true :false;

	}

	/**
	 * Copies the database from the assets folder to the newly created database in the systemm folder.
	 * Bytestream transfer occurs here.
	 * @throws IOException 
	 */
	private void copyDatabase() throws SQLException, IOException{
		
		//Open database as input stream
		InputStream streamInput = context.getAssets().open(DATABASE_NAME);
		
		// Path to the database
		
		String FileName = DATABASE_PATH + DATABASE_NAME;
		
		//Open the database as output stream
		
		OutputStream streamOutput = new FileOutputStream(FileName);
		
		// Transfer bytes via the byte stream from input to output
		
		byte[] buffer = new byte[1024];
		int length;
		while((length=streamInput.read(buffer))>0){
				streamOutput.write(buffer,0,length);
		}
			//close all of the streams that you have opened
		streamOutput.flush();
		streamOutput.close();
		streamInput.close();
		
		}
		
	public void openDatabase() throws SQLException{
		
		// Open the database
		
		String path = DATABASE_PATH + DATABASE_NAME;
		db = SQLiteDatabase.openDatabase(path,null,SQLiteDatabase.OPEN_READONLY);
	}
		@Override
			public synchronized void close() {
			if(db !=null)
					db.close();
			super.close();
			
		}
		
		public Context getContext( Context context){
			return context;
			
		}
		
		public void InsertIntoDatabase() throws SQLException{
			ManageSQLDbHelper myDatabaseHelper  = new ManageSQLDbHelper(getContext(context));
			
			// Get repository in write mode
			SQLiteDatabase db= myDatabaseHelper.getWritableDatabase();
			
			// Create a relationship between values, where column names become the keys
			ContentValues cv= new ContentValues();
			cv.put(DatabaseTable.COLUMN_NAME_ENTRY_ID,entry_id);
			cv.put(DatabaseTable.COLUMN_NAME_TITLE,title);
			
			
			
			
			
			
		}
		
	
		}//end class
	
	
		






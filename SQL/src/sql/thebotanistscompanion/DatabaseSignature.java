package sql.thebotanistscompanion;

import android.provider.BaseColumns;

public final class DatabaseSignature {

	
	public DatabaseSignature(){} //Prevents someone creating an instance of this class by mistake
	
	/* Nested class that defines what a table must look like in the database */
	
	public static abstract class DatabaseTable implements BaseColumns{
		public static final String TABLE_NAME ="entry_to_table";
		public static final String COLUMN_NAME_ENTRY_ID = "entry_id";
		public static final String COLUMN_NAME_TITLE ="title";
		
	}
}

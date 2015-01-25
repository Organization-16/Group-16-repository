package com.example.botanistcompanionapp;

import java.util.ArrayList;
import java.util.List;

import com.example.botanistcompanionapp.Fragment_GPS.NewRecordCommunicator;
import com.example.botanistcompanionapp.Fragment_Select_Plant.PlantListCommunicator;
import com.example.botanistcompanionapp.Fragment_Site_selection.PickRecordCommunicator;

import recordPackage.Record;
import recordPackage.RecordManagement;
import utilities.GPSLocation;
import utilities.Plant;
import utilities.PlantListInteracter;
import android.content.SharedPreferences;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

public class EditRecord extends FragmentActivity implements NewRecordCommunicator, PlantListCommunicator, PickRecordCommunicator{

	private static final String PREFS_NAME = "BOTANIST";
	private PlantListInteracter plantlist;
	private RecordManagement recordmanager;
	private Fragment_Site_selection firstFragment;
	private Fragment_Userdata secondFragment;
	private Record rec = null;
	private GPSLocation gps;
	private Location loc;
	private String email;
	private String name;
	private String phone;
	private List<Record> recordlist;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		recordmanager = new RecordManagement();
		plantlist = new PlantListInteracter();
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_edit_record);//error bit
		
		if (findViewById(R.id.fragment_container) != null) {
			if (savedInstanceState != null) {
				return;
			} }

		recordlist = recordmanager.getAllRecords();
		
		getActionBar().setTitle("Edit or delete a Record");
		
		firstFragment = new Fragment_Site_selection();
		firstFragment.setArguments(getIntent().getExtras());
		getSupportFragmentManager().beginTransaction().add(R.id.fragment_container, firstFragment).commit();
		
	}

	public void LaunchRecordedit(Record rec){
		getActionBar().setTitle("Editing a Record");
		this.rec = rec;
		gps = new GPSLocation(this);
		gps.startGPS();
		
		// Get from the SharedPreferences
		SharedPreferences settings = getApplicationContext().getSharedPreferences(PREFS_NAME, 0);
		email = settings.getString("EMAIL", null);
		name = settings.getString("NAME", null);
		phone = settings.getString("PHONE", null);
		
		Bundle args1 = new Bundle();
		args1.putSerializable("RECORD", rec);
		args1.putString("NAME", name);
		args1.putString("EMAIL", email);
		args1.putString("PHONE", phone);
		
		secondFragment = new Fragment_Userdata();
		secondFragment.setArguments(getIntent().getExtras());
		secondFragment.setArguments(args1);
		getSupportFragmentManager().beginTransaction().add(R.id.fragment_container, secondFragment).commit();
		
		plantlist = tempListCreator();
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.edit_record, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();
		if (id == R.id.action_settings) {
			return true;
		}
		return super.onOptionsItemSelected(item);
	}

	public Location getLocation(){
		loc = gps.getLocation();
		return loc;
	}
	
	@Override
	protected void onStop (){
		super.onStop();
		gps.stopGPS();
	}

	public PlantListInteracter tempListCreator(){
		PlantListInteracter temp = new PlantListInteracter();
		for(int i = 0; i < 10; i++){
			temp.addplant("latin"+i, "common"+i);
		}
		return temp;
	}
	
	/**
	 * this is the final method that is to end the activity.
	 */
	@Override
	public void ReturnFinalRecord(Record rec,String email,String name, String phone) {
		this.rec = rec;
		this.email = email;
		this.name = name;
		this.phone = phone;
		
		SharedPreferences settings = getApplicationContext().getSharedPreferences(PREFS_NAME, 0);
		SharedPreferences.Editor editor = settings.edit();
		editor.putString("EMAIL", email);
		editor.putString("PHONE", phone);
		editor.putString("NAME", name);
		editor.commit();
		
		recordmanager.editARecord(rec);
		recordmanager.addRecordToList();
		recordmanager.save();
		
		finish();
	}

	@Override
	public ArrayList<Plant> getPlantlist() {
		return plantlist.getAllPlants();
	}
	
	@Override
	public List<Record> getRecordlist() {
		recordlist = recordmanager.getAllRecords();
		return recordlist;
	}
	
}

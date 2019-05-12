<?php
// Formats an integer to a proper price format, e.g. € 17,95
function formatPrice($price)
{
	$formatted = number_format($price, 2, ',', '');
	$formatted = '&euro; ' . $formatted;
	
	return $formatted;
}


// Checks if a member goes on camp in the near future, returns first event ID or false
function goesOnCamp($member)
{
	$result = false;
	
	$dates = $member->events()->where('type', 'kamp')->orderBy('datum_start')->pluck('datum_start','id')->toArray();

	foreach ($dates as $id => $date) {
		if (strtotime($date) > time() ) {
			$result = $id;
			break;
		}
	}
	
	return $result;
}

// Checks the current status of coverage for a certain course at a certain event, returns true or false
function checkCoverage($camp, $course_id)
{

	$memberIDs = $camp->members->pluck('id')->toArray();

	// Obtain members that have this course
	$result = \DB::table('course_member')
		->whereIn('member_id', $memberIDs)
		->where('course_id', $course_id)
		->get();
	
	$numbers['m'] = count($result);
	$levels['m'] = [];
	foreach ($result as $row)
	{
		$levels['m'][] = $row->klas;
	}
	
	// Obtain participants that have this course
	$result = \DB::table('course_event_participant')
		->where('event_id', $camp->id)
		->where('course_id', $course_id)
		->join('participants', 'course_event_participant.participant_id', '=', 'participants.id')
		->select('participants.id', 'participants.klas')
		->get();
	
	$numbers['p'] = count($result);
	$levels['p'] = [];
	foreach ($result as $row)
	{
		$levels['p'][] = $row->klas;
	}
	
	// Now determine the status of this course...
	$status = true;
	
	// Start by checking if just the number of members is sufficient
	if (3 * $numbers['m'] < $numbers['p'])
	{
		$status = false;
	}
	else
	{
		// If the number of members is sufficient, then check if their levels are
		
		// Create 'triple-array' for member levels
		$m = [];
		foreach ($levels['m'] as $val)
		{
			$m[] = $val;
			$m[] = $val;
			$m[] = $val;
		}

		$p = $levels['p'];

		// Sort both arrays from high to low
		rsort($m, SORT_NUMERIC);
		rsort($p, SORT_NUMERIC);
		
		// Compare element-wise
		foreach ($p as $key => $value)
		{
			if ($value > $m[$key])
			{
				$status = false;
			}
		}
	}

	return $status;
	
}

# Function to create a bar chart for review results using LavaCharts
function createReviewChart($event, $name, $options, $member = NULL) {
	$dt = \Lava::DataTable();
	$dt->addStringColumn('Optie');
	$dt->addNumberColumn('Aantal');
	
	if (is_null($member)) {
		$q = $event->reviews()
				->select($name, \DB::raw('count(*) as total'))
				->groupBy($name)
				->pluck('total', $name)->toArray();
	} else {
		$q = $member->reviews()
				->where('event_id', $event->id)
				->select($name, \DB::raw('count(*) as total'))
				->groupBy($name)
				->pluck('total', $name)->toArray();
	}
	
	foreach ($options as $n => $t) {
		if (array_key_exists($n, $q)) {
			$dt->addRow([$t, $q[$n]]);
		} else {
			$dt->addRow([$t, 0]);
		}
	}
	
	\Lava::BarChart($name, $dt, [
		'width' => '100%',
		'height' => '250',
		'chartArea' => [
			'top' => 25,
			'left' => '25%',
		//	'height' => 180,
			'width' => '70%'
		],
		'fontSize' => 14,
		'hAxis' => [
			'minValue' => 0,
			'gridlines' => [
				'count' => -1
			]
		],
		'legend' => [
			'position' => 'none'
		]
	]);
}


/* Zip function from David Walsh, https://davidwalsh.name/create-zip-php */
function create_zip($files = array(),$destination = '',$overwrite = false, $flat = true) {

	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return "Error - was told not to overwrite existing zip file"; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();

		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE|ZIPARCHIVE::CREATE : ZIPARCHIVE::CREATE) !== true) {
			return "Error opening zip file for writing";
		}

		//add the files
		foreach($valid_files as $file) {
			if ($flat) {
				$local = array_slice(explode('/', $file), -1)[0];
				//$debug .= $local;
				$zip->addFile($file, $local);
			} else {
				$zip->addFile($file,$file);
			}
			
		}
		//debug
		//$debug = 'The zip archive contains '. $zip->numFiles . ' files with a status of ' . $zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		//return file_exists($destination);
		return true;
	}
	else
	{
		return "Error - no valid files found";
	}
}

?>
<?php

use Illuminate\Support\Facades\DB;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava; 

// Checks the current status of coverage for a certain course at a certain event, returns true or false
function checkCoverage($camp, $course_id)
{

	$memberIDs = $camp->members->pluck('id')->toArray();

	// Obtain members that have this course
	$result = DB::table('course_member')
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
	$result = DB::table('course_event_participant')
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
	$dt = Lava::DataTable();
	$dt->addStringColumn('Optie');
	$dt->addNumberColumn('Aantal');
	
	if (is_null($member)) {
		$q = $event->reviews()
				->select($name, DB::raw('count(*) as total'))
				->groupBy($name)
				->pluck('total', $name)->toArray();
	} else {
		$q = $member->reviews()
				->where('event_id', $event->id)
				->select($name, DB::raw('count(*) as total'))
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
	
	Lava::BarChart($name, $dt, [
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

?>
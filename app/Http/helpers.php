<?php

use Illuminate\Support\Facades\DB;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava; 

// Checks the current status of coverage for a certain course at a certain event, returns true or false
// TODO this is used by profile controller, how does the coverage checker work? We can probably combine those methods
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

?>
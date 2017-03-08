<?php
/**
 * @name      Role Playing System Events
 * @copyright Cody Williams
 * @license   MIT License
 *
 *
 * @version 1.0
 *
 */

//Load settings and database connetion.
$settings_loc = __DIR__ . '/settings.php';
require_once($settings_loc);

//If no year is given, do not return anything.  Otherwise we get everything.
if(empty($_REQUEST['year']))
	return;

//We only return the phases asked for.
if(!empty($_REQUEST['phases']))
	$phases['phase'] = json_decode($_REQUEST['phases']);
else
	return;

//If they don't send a timezone, we will stick with UTC
$timezone = !empty($_REQUEST['timezone']) ? $_REQUEST['timezone'] : 'UTC';

//Create the timezone object for original and requested timezones
$original_timezone = new DateTimeZone('UTC');
$new_timezone = new DateTimeZone($timezone);

//Set the range for the year.
$year = (int) $_REQUEST['year'];
$start = $year-1 . '-12-31';
$end = $year+1 . '-01-01';

//Set the where condition for the year.  [<>] searches for the phase date between two dates in array
$date = array(
	'phase_date[<>]' => array($start,$end),
	);

//Combine our where conditions
$where = array_merge($date, $phases);

//Find those moon phases.
$data = $database->select('phases', ['phase', 'phase_date', 'phase_time'], $where);

//Create DateTime object for each phase from phase date and phase time with UTC timezone.  Then set timezone to requested timezone.
//Overwrite phase_date and phase_time with new values.  phase is passed by reference to enable overwriting.
foreach($data as &$phase)
{
	$date = new DateTime($phase['phase_date'] . ' ' . $phase['phase_time'], $original_timezone);
	$date->setTimeZone($new_timezone);
	$phase['phase_date'] = $date->format('Y-m-d');
	$phase['phase_time'] = $date->format('H:i:s');
	unset($date);
}

//Output json.
header('Content-type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);


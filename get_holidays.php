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

//We only return the holidays asked for.
$holidays = array();
if(!empty($_REQUEST['holidays']))
{
	//Set the where condition for the holidays.  Medoo will search for title IN array
	$holidays =  array('title' => json_decode($_REQUEST['holidays']));
}
else
	return;

//Set the range for the year.
$year = (int) $_REQUEST['year'];
$start = $year . '-01-01';
$end = $year . '-12-31';

//Set the where condition for the year.  [<>] searches for the holiday date between two dates in array
$dates = array(
	'holiday_date[<>]' => array($start,$end),
	);
	
//Combine our where conditions.
$where = array_merge($dates, $holidays);

//Find those holidays
$data = $database->select('holidays', ['title', 'holiday_date'], $where);

//Output json encoded array.
header('Content-type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);



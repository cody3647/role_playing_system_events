<?php
require_once  'Medoo.php';

$database = new Medoo\Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'events',
	'server' => 'localhost',
	'username' => 'development',
	'password' => 'testdatabase',
	'charset' => 'utf8', 
]);

$christian = array("Ash Wednesday", "Palm Sunday", "Good Friday", "Easter", "Ascension Day", "Whit Sunday -- Pentecost", "Trinity Sunday", "First Sunday in Advent"); //0
$jewish = array("First Day of Pesach (Passover)", "Shavuot (Feast of Weeks)", "Rosh Hashanah (Jewish New Year)", "Yom Kippur (Day of Atonement)", "First Day of Succoth (Feast of Tabernacles)", "First Day of Hanukkah (Festival of Lights)"); //1
$muslim = array("First Day of Ramadan", "First Day of Shawwal", "Islamic New Year"); //2

$dir_iterator = new DirectoryIterator("holidays");
$iterator = new IteratorIterator($dir_iterator);
// could use CHILD_FIRST if you so wish
$holidays = array();
$db_holidays = array();
foreach ($iterator as $file) {
     if (!$file->isDot())
	 {
		$json_holiday = file_get_contents($file->getPathname());
		$holidays = array_merge($holidays, json_decode($json_holiday, true) );
		unset($json_holiday);
	 }
}

ksort($holidays);
echo '<pre>';
foreach($holidays as $date => $holiday)
{
	foreach($holiday as $title)
	{
		$religion = in_array($title, $jewish) ? 1 : (in_array($title, $muslim) ? 2 : 0 );
		$db_holidays[] = array(
			'title' => $title,
			'holiday_date' => $date,
			'religion' => $religion
		);;
	}
}
unset($holidays);

if(isset($_REQUEST['database']))
		$database->insert('holidays', $db_holidays);

print_r($db_holidays);
echo '</pre>';

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

$dir_iterator = new DirectoryIterator("phases");
$iterator = new IteratorIterator($dir_iterator);
// could use CHILD_FIRST if you so wish
$phases = array();
$db_phases = array();
foreach ($iterator as $file) {
     if (!$file->isDot())
	 {
		$json_phase = file_get_contents($file->getPathname());
		$phases = array_merge($phases, json_decode($json_phase, true) );
		unset($json_phase);
	 }
}

ksort($phases);
echo '<pre>';
foreach($phases as $date => $phase)
{
	$db_phases[] = array(
		'phase' => $phase[0],
		'phase_date' => $date,
		'phase_time' => $phase[1]
	);
}
unset($phases);

if(isset($_REQUEST['database']))
		$database->insert('phases', $db_phases);

print_r($db_phases);
echo '</pre>';

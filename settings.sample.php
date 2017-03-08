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

//Load Medoo
require_once  'Medoo.php';

//Connect to database.
$database = new Medoo\Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => '',
	'server' => 'localhost',
	'username' => '',
	'password' => '',
	'charset' => 'utf8', 
]);
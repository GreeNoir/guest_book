<?php

// This is the database connection configuration.
return array(
//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

    'class' => 'CDbConnection',
	'connectionString' => 'mysql:host=mysql55.ace;dbname=pmsc_test',
	'emulatePrepare' => true,
	'username' => 'pmiuser',
	'password' => 'dimamolodec',
	'charset' => 'utf8',

);
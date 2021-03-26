<?php
	
	$config = require_once 'config.php';
	
	try {
		
		$database = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset=utf8", $config['user'], $config['password'],
		[PDO::ATTR_EMULATE_PREPARES => false, 
		 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		 
	} catch (PDOException $error) {
		
		//echo $error->getMessage();
		exit ('<div style ="color: red; font-size: 15px;"> Server error, please try again later </div>');
	}
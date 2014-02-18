<?php
	require_once 'HelpDesk.php';
	$config = include('config.php');
	date_default_timezone_set('Eroupe/Moscow');

	$hd = new HelpDesk($config['connectionString'], $config['email'], $config['password']);
	$hd->setOrganization($config['organization']);
	$hd->checkMessages();
?>
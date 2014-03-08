<?php

date_default_timezone_set('Europe/Moscow');
require_once 'libs/Autoloader.php';
require_once 'vendor/ActiveRecord/ActiveRecord.php';
require_once 'vendor/ImapMailbox.php';
require_once 'config.php';

// Active Record
Activerecord\Connection::$datetime_format = Activerecord\DateTime::$FORMATS['iso8601'];
ActiveRecord\Config::initialize(function(ActiveRecord\Config $c) {
    $config = new Config();
    $c->set_model_directory(__DIR__ . '/models');
    $c->set_connections(array('development' =>
        $config->db_type . '://'
        . $config->db_user . ':'
        . $config->db_password . '@'
        . $config->db_host . '/'
        . $config->db_name . '?charset=utf8'
    ));
});
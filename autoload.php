<?php

// Check if the app is configured
if(!file_exists(__DIR__ . '/configuration/configuration.php')) {
    header("Location: install.php");
}

// Load config
require __DIR__ . '/configuration/configuration.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

// Autoload
function customCIAutoload($classname){
  if(file_exists($file = __DIR__ . '/class/' . $classname . '.class.php') ||
     file_exists($file = __DIR__ . '/class/' . $classname . '.php') ||
     file_exists($file = __DIR__ . '/controllers/' . $classname . '.php')){
    require $file;
  }
}

spl_autoload_register('customCIAutoload');

// Database
$db = DBFactory::setMySQLConnection($db_host, $db_username, $db_password, $db_database, $db_port);

// Timezone
date_default_timezone_set("Europe/Paris");
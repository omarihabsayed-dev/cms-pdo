<?php
// Autoloader for classes
require_once "autoloader.php";
// Start session
session_start();
// Include the main configuration file
require_once "config/config.php";
// Include helper functions
require_once "helpers.php";
// Define global constants
define('APP_NAME', 'CMS PDO System');
?>
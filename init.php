<?php
// Start session
session_start();
// Include the main configuration file
require_once "config/config.php";
// Load database connection
require_once "classes/database.php";
// Include helper functions
require_once "helpers.php";
// Define global constants
define('APP_NAME', 'CMS PDO System');
?>
<?php

require_once 'vendor\autoload.php';

use VintorezzZ\BackendPhpLearning\UI\HTTP\HttpApplication;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$inputJSON = file_get_contents('php://input');
$application = new HttpApplication();
echo $application->runRequest($inputJSON, $_SERVER);
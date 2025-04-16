<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
error_log("index.php Inizio esecuzione", 0);

require_once __DIR__ .'/config/bootstrap.php';
require_once __DIR__ .'/config/StartSmarty.php';

$fc = new CFrontController();
$fc->run();

/**
 * To verify that smarty works out, you can run the 
 * following method:
 * $smarty = StartSmarty::configuration();
 * $smarty->testInstall();
 */

?>
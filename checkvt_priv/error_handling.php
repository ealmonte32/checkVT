<?php
//error reporting
error_reporting(E_ALL); //report all errors and warnings

//displaying of errors
ini_set('display_errors', 0); //displaying of errors to users: 0 (off) or 1 (on)
ini_set('display_startup_errors', 0); //displaying of startup errors: 0 (off) or 1 (on)

//error logging
ini_set('log_errors', 1); //log errors: 0 (off) or 1 (on)

//if we want to make sure that display_errors was properly set, we use the following if statement
if ( ini_set('display_errors', 0) === false ) {
    throw new Exception('Unable to set display_errors.');
}

//if we want to make sure that display_startup_errors was properly set, we use the following if statement
if ( ini_set('display_startup_errors', 0) === false ) {
    throw new Exception('Unable to set display_startup_errors.');
}

//reminder: to see parse errors the actual php.ini file must be modified to have display_errors = on

?>
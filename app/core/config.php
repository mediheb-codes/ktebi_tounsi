<?php

define("WEBSITE_TITLE", "commerce tounsi");

//database
define("DB_NAME", "ecommerce_tounsi");
define("DB_USER", "root");
//put your root password
define("DB_PASS", "");
define("DB_TYPE", 'mysql');
define("DB_HOST", "localhost");

define("DEBUG", true);

if (DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

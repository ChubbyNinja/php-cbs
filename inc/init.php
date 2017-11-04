<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:43
 */


require INC_PATH . 'autoload.php';
require INC_PATH . 'rb.php';

/**
 * MySQL Hostname, usually localhost or 127.0.0.1
 */
define('MYSQL_HOST','localhost');
/**
 * MySQL Username
 */
define('MYSQL_USER','phpcbs');
/**
 * MySQL Database
 */
define('MYSQL_DB', 'phpcbs');
/**
 * MySQL Password
 */
define('MYSQL_PASS','phpcbs');

\R::setup( "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB, MYSQL_USER, MYSQL_PASS );

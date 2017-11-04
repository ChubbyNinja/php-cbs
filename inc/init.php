<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:43
 */


require INC_PATH . 'autoload.php';
require INC_PATH . 'rb.php';

define('MYSQL_HOST','localhost');
define('MYSQL_USER','phpcbs');
define('MYSQL_DB', 'phpcbs');
define('MYSQL_PASS','phpcbs');

\R::setup( "mysql:host=127.0.0.1;dbname=".MYSQL_DB,
    MYSQL_USER, MYSQL_PASS );

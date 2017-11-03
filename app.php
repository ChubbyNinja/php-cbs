<?php
namespace CBS;
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:42
 */

//error_reporting(0);

define('APPLICATION_ROOT', __DIR__);
define('INC_PATH', APPLICATION_ROOT .'/inc/');
define('CLASS_PATH', APPLICATION_ROOT .'/classes/');

require INC_PATH . 'init.php';

$app = new app();
$app->init();
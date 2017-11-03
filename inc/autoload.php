<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:55
 */

function cbsAutoloader($class) {

    if( substr($class,0,3) != 'CBS' ) {
        return;
    }
    list($namespace, $className) = explode('\\', $class);

    include CLASS_PATH . $className . '.php';
}

spl_autoload_register('cbsAutoloader');
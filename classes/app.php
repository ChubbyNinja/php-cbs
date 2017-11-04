<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 03/11/2017
 * Time: 09:38
 */

namespace CBS;


class app
{
    public function init() {

        $this->checkCli();
        $this->checkForArgs();
    }

    private function checkCli() {
        if( php_sapi_name() !== 'cli' ) {
            die('PHPCLI Application only.');
        }
    }

    private function checkForArgs() {
        global $argv;

        if( isset($argv[1])){
            switch( $argv[1] ) {
                case 'ui':
                    $this->launchUI();
                    break;
            }
            return;
        }
        $this->launchUI();
    }

    private function launchUI() {
        $menu = new menu();
        $menu->printWelcomeScreen();
        $menu->printMainMenu();

    }
}
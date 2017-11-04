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
        $this->checkPrerequisite();
        $this->checkForArgs();
    }

    private function checkPrerequisite() {
        $connected = \R::testConnection();

        if( !$connected ) {
            output::responseError('Cannot establish connection to database, please check inc/init.php');
            exit;
        }

        if( !extension_loaded('mbstring') ) {
            output::responseError('PHP module mbstring is required.');
            exit;
        }
    }

    private function checkCli() {
        if( php_sapi_name() !== 'cli' ) {
            die('PHP-CLI Application only.');
        }
    }

    private function checkForArgs() {
        global $argv;

        if( isset($argv[1])){
            switch( $argv[1] ) {
                case 'ui':
                    $this->launchUI();
                    break;

                case 'addmovie':
                    $m = new movies();
                    $m->addMovieCLI();
                    break;

                case 'listmovies':
                    $m = new movies();
                    $m->getMovieList();
                    break;

                case 'delmovie':
                    $m = new movies();
                    $m->deleteMovieCLI();
                    break;

                case 'addbooking':
                    $m = new movies();
                    $m->addBookingCLI();
                    break;

                case 'listbookings':
                    $m = new movies();
                    $m->listBookingsCLI();
                    break;

                case 'delbooking':
                    $m = new movies();
                    $m->deleteBookingCLI();
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

    public static function isWIN() {
        return ((substr(PHP_OS,0,3) == 'WIN') ? true : false);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 03/11/2017
 * Time: 09:38
 */

namespace CBS;


/**
 * Class app
 * @package CBS
 */
class app
{
    /**
     * Start the application and check what environment we are working in
     */
    public function init() {

        $this->checkCli();
        $this->checkPrerequisite();
        $this->checkForArgs();
    }

    /**
     * Check connection to database is OK, and mbstring is available
     */
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

    /**
     * Check we are running in the CLI
     */
    private function checkCli() {
        if( php_sapi_name() !== 'cli' ) {
            die('PHP-CLI Application only.');
        }
    }

    /**
     * Check if we should launch the user interface, or single line commands
     */
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

                case 'help':
                    $this->printCommands();
                    break;
            }
            return;
        }
        $this->launchUI();
    }

    /**
     * Launch the user interface
     */
    private function launchUI() {
        $menu = new menu();
        $menu->printWelcomeScreen();
        $menu->printMainMenu();

    }

    /**
     * Check if we are running under Windows
     * @return bool
     */
    public static function isWIN() {
        return ((substr(PHP_OS,0,3) == 'WIN') ? true : false);
    }

    private function printCommands(){
        output::lineBreak('-');
        output::message('Command        |   Action          |   Example');
        output::lineBreak('-');
        output::message('addmovie       |   Add movie       |   addmovie "Movie Title" "Movie Date" "Movie Time"');
        output::message('listmovies     |   List movies     |   listmovies');
        output::message('delmovie       |   delete movie    |   delmovie "Movie ID"');
        output::message('addbooking     |   Add booking     |   addbooking "Movie ID" "Customer Name" "Total seats"');
        output::message('listbookings   |   List bookings   |   listbookings "Movie ID" (optional)');
        output::message('delbooking     |   delete booking  |   delbooking "Movie ID"');
        output::message('ui             |   User interface  |   ui');
        output::message('help           |   Display help    |   help');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:52
 */

namespace CBS;


class menu
{

    public static function loadWelcomeScreen() {
        output::clearScreen();
        output::lineBreak('~');
        output::lineBreak('~','half');
        output::message('Welcome To CBS - Cinema Booking System', 'center');
        output::message('Danny Hearnah', 'center');
        output::lineBreak('~','half');
        output::blankRow();
        output::blankRow();
    }

    public static function loadMainMenu() {

        output::message('What would you like to do?', 5);
        output::message('1. Add a movie', 5);
        output::message('2. Remove a movie', 5);
        output::message('3. List all movies', 5);
        output::message('4. View movie details', 5);
        output::message('5. Add a booking', 5);
        output::message('6. Remove a booking', 5);
        output::message('7. List bookings', 5);
        output::blankRow();

        $response = input::getIntResponse(1,7);


        switch( $response ) {
            case 1:
                movies::addMovie();
                break;
        }


    }
}
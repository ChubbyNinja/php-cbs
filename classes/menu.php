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

    public function printInit() {
        $this->printWelcomeScreen();
        $this->printMainMenu();
    }

    public function printWelcomeScreen() {
        output::clearScreen();
        output::lineBreak('~');
        output::lineBreak('~','half');
        output::message('Welcome To CBS - Cinema Booking System', 'center');
        output::message('Danny Hearnah', 'center');
        output::lineBreak('~','half');
        output::blankRow();
        output::blankRow();
    }

    public function printMainMenu() {

        output::message('What would you like to do?', 5);
        output::message('1. Add a movie', 5);
        output::message('2. Remove a movie', 5);
        output::message('3. List all movies', 5);
        output::message('4. View movie details', 5);
        output::message('5. Add a booking', 5);
        output::message('6. Remove a booking', 5);
        output::message('7. List bookings', 5);
        output::blankRow();

        $input = new input();
        $response = $input->getIntResponse(1,7)->getInputData();


        switch( $response ) {
            case 1:
                $movies = new movies();
                $movies->addMovie();
                break;

            case 2:
                $movies = new movies();
                $movies->deleteMovie();
                break;

            case 3:
                $movies = new movies();
                $movies->ListMovies();
                break;

            case 4:
                $movies = new movies();
                $movies->printMovie();
                break;

            case 5:
                $movies = new movies();
                $movies->addBooking();
                break;

            case 6:
                $movies = new movies();
                $movies->deleteBooking();
                break;

            case 7:
                $movies = new movies();
                $movies->listBookings();
                break;
        }


    }
}
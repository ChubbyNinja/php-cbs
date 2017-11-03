<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 03/11/17
 * Time: 01:01
 */

namespace CBS;


class movies
{

    private function loadMovieWelcomeScreen(){
        menu::loadWelcomeScreen();
        output::message('Adding a new movie','center');
        output::blankRow();
    }

    public static function addMovie(){

        self::loadMovieWelcomeScreen();

        output::message('What is the movie title?', 5);
        $movieTitle = input::getStringResponse();

        output::blankRow();
        output::message('What date is this showing?', 5);
        output::message('Hint: Use dd/mm/YYYY or "next tuesday"', 5);
        $movieDate = input::getDateResponse();

        output::blankRow();
        output::message('What time is this showing?', 5);
        output::message('Hint: Use HH:mm i.e 15:30', 5);
        $movieTime = input::getTimeResponse();

        self::addMovieConfirm($movieTitle,$movieDate,$movieTime);

    }

    private function addMovieConfirm($title,$date,$time){

        self::loadMovieWelcomeScreen();
        output::message('Please confirm the following details are correct:',5);
        output::blankRow();
        output::message('Movie Title: ' . $title,5);
        output::message('Movie Air Date: ' . $date,5);
        output::message('Movie Air Time: ' . $time,5);

        output::blankRow();
        output::message('To confirm type: Y');

        $response = input::getStringResponse();

        if( strtolower($response) == 'y' ) {
            $response = self::addMovieToDatabase($title,$date,$time);

            if( $response ) {
                echo $response;
            }
        }
    }

    private function addMovieToDatabase($title,$date,$time) {
        $obj = \R::dispense('movie');
        $obj->movieTitle = $title;
        $obj->movieShowing = strtotime( $date . ' ' . $time);
        $obj->movieAdded = time();

        return \R::store($obj);
    }

}
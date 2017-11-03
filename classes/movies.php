<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 03/11/17
 * Time: 01:01
 */

namespace CBS;


class movies extends menu
{

    private $movieTitle;
    private $movieDate;
    private $movieTime;
    private $movieObject;
    private $movieId;
    private $movieSeats;
    private $bookingData;

    /**
     * @return mixed
     */
    public function getMovieTitle()
    {
        return $this->movieTitle;
    }

    /**
     * @param mixed $movieTitle
     */
    public function setMovieTitle($movieTitle)
    {
        $this->movieTitle = $movieTitle;
    }

    /**
     * @return mixed
     */
    public function getMovieDate()
    {
        return $this->movieDate;
    }

    /**
     * @param mixed $movieDate
     */
    public function setMovieDate($movieDate)
    {
        $this->movieDate = $movieDate;
    }

    /**
     * @return mixed
     */
    public function getMovieTime()
    {
        return $this->movieTime;
    }

    /**
     * @param mixed $movieTime
     */
    public function setMovieTime($movieTime)
    {
        $this->movieTime = $movieTime;
    }

    /**
     * @return mixed
     */
    public function getMovieObject()
    {
        return $this->movieObject;
    }

    /**
     * @param mixed $movieObject
     */
    public function setMovieObject($movieObject)
    {
        $this->movieObject = $movieObject;
    }

    /**
     * @return mixed
     */
    public function getMovieId()
    {
        return $this->movieId;
    }

    /**
     * @param mixed $movieId
     */
    public function setMovieId($movieId)
    {
        $this->movieId = $movieId;
    }

    /**
     * @return mixed
     */
    public function getMovieSeats()
    {
        return $this->movieSeats;
    }

    /**
     * @param mixed $movieSeats
     */
    public function setMovieSeats($movieSeats)
    {
        $this->movieSeats = $movieSeats;
    }

    /**
     * @return mixed
     */
    public function getBookingData()
    {
        return $this->bookingData;
    }

    /**
     * @param mixed $bookingData
     */
    public function setBookingData($bookingData)
    {
        $this->bookingData = $bookingData;
    }





    public function printMovieWelcomeScreen($message){
        $this->printWelcomeScreen();
        output::message($message,'center');
        output::blankRow();
    }

    public function addMovie(){

        $this->printMovieWelcomeScreen('Adding a new movie');

        output::message('What is the movie title?', 5);
        $movieTitle = (new input())->getStringResponse()->getInputData();
        $this->setMovieTitle($movieTitle);

        output::blankRow();
        output::message('What date is this showing?', 5);
        output::message('Hint: Use dd/mm/YYYY or "next tuesday"', 5);
        $movieDate = (new input())->getDateResponse()->getInputData();
        $this->setMovieDate($movieDate);

        output::blankRow();
        output::message('What time is this showing?', 5);
        output::message('Hint: Use HH:mm i.e 15:30', 5);
        $movieTime = (new input())->getTimeResponse()->getInputData();
        $this->setMovieTime($movieTime);

        $this->addMovieConfirm();

    }


    private function confirmMovie() {
        $this->printMovieWelcomeScreen('Please confirm the following details are correct:');
        output::message('Movie Title: ' . $this->getMovieTitle(),5);
        output::message('Movie Air Date: ' . $this->getMovieDate(),5);
        output::message('Movie Air Time: ' . $this->getMovieTime(),5);

        output::blankRow();
        output::message('To confirm type: Y');
        $response = (new input())->getStringResponse()->getInputData();
        return $response;

    }
    private function addMovieConfirm(){

      $response = $this->confirmMovie();

        if( strtolower($response) == 'y' ) {
            $inserted = $this->addMovieToDatabase();
            if( $inserted ) {
                $this->loadMovieData();
                $this->printMovieData();
            }

        }
    }


    private function deleteMovieConfirm() {

        $response = $this->confirmMovie();

        if( strtolower($response) == 'y' ) {
            $this->deleteMovieFromDatabase();
        }
        return true;
    }

    private function addMovieToDatabase() {
        $obj = \R::dispense('movie');
        $obj->movieTitle = $this->getMovieTitle();
        $obj->movieShowing = strtotime( $this->getMovieDate() . ' ' . $this->getMovieTime());
        $obj->movieAdded = time();
        $obj->movieSeats = 10;

        $movieId = \R::store($obj);
        $this->setMovieId($movieId);
        return true;
    }

    private function deleteMovieFromDatabase() {
        \R::trash($this->getMovieObject());
        return true;
    }

    public function loadMovieData($id = null) {
        if( $id ) {
            $this->setMovieId($id);
        }

        if( !$this->getMovieId() ) {
            output::responseError('No movie ID set');
        }

        $obj = \R::load('movie', $this->getMovieId());

        if( !$obj->getID() ) {
            output::responseError('No movie found');
        }

        $this->setMovieTitle($obj->movieTitle);
        $this->setMovieDate(date('d/m/Y',$obj->movieShowing));
        $this->setMovieTime(date('H:i',$obj->movieShowing));
        $this->setMovieSeats($obj->movieSeats);
        $this->setMovieObject($obj);

        $bookings = new bookings($this);

        $this->setBookingData($bookings);

    }

    public function printMovieData() {
        $this->printMovieWelcomeScreen('Movie Details');
        output::message('Movie ID: ' . $this->getMovieId(),5);
        output::blankRow();
        output::message('Movie Title: ' . $this->getMovieTitle(),5);
        output::message('Movie Air Date: ' . $this->getMovieDate(),5);
        output::message('Movie Air Time: ' . $this->getMovieTime(),5);
        output::blankRow();
        output::message('Total Seats: ' . $this->getMovieSeats(), 5);
        output::message('Available Seats: ' . $this->getBookingData()->availableSeats(), 5);
        output::blankRow();
        output::message('Total Bookings: ' . $this->getBookingData()->getTotalBookings(), 5);


        $this->waitForReturnToMenu();

    }

    public function deleteMovie() {
        $this->printMovieWelcomeScreen('Movie List');
        $this->getMovieList();
        output::message('What movie ID would you like to remove?', 5);
        $movieId = (new input())->getIntResponse(0,99,true)->getInputData();

        if( $movieId ) {
            $m = new movies();
            $m->loadMovieData($movieId);
            $m->deleteMovieConfirm();
        } else {
            $this->printInit();
        }
    }

    public function getMovieList() {
        $obj = \R::findAll('movie');

        $str = 'ID: %s | %s @ %s | %s';

        if( $obj ) {
            foreach ($obj as $item) {
                $m = new movies();
                $m->loadMovieData($item->getId());
                output::message(sprintf($str, $m->getMovieId(), $m->getMovieDate(), $m->getMovieTime(), $m->getMovieTitle()), 5);
            }
        }
        output::blankRow();
    }

    public function listMovies(){
        $this->printMovieWelcomeScreen('Movie List');
        $this->getMovieList();

        $this->waitForReturnToMenu();
    }

    public function waitForReturnToMenu() {
        output::blankRow();
        output::message('Press return to load menu', 5);
        (new input())->getStringResponse(true)->getInputData();

        $this->printInit();
    }

    public function printMovie() {
        $this->printMovieWelcomeScreen('Movie List');
        $this->getMovieList();

        output::message('What movie ID would you like to view?', 5);
        $movieId = (new input())->getIntResponse(0,99,true)->getInputData();

        if( $movieId ) {
            $m = new movies();
            $m->loadMovieData($movieId);
            $m->printMovieData();
        } else {
            $this->printInit();
        }

    }


    public function addBooking()
    {
        $this->printMovieWelcomeScreen('Movie List');
        $this->getMovieList();
        output::message('What movie ID would you like to book?', 5);
        $movieId = (new input())->getIntResponse(0, 99, true)->getInputData();

        if ($movieId) {
            $m = new movies();
            $m->loadMovieData($movieId);
            $booking = new bookings($m);

            $this->printMovieWelcomeScreen('Movie: ' . $m->getMovieTitle() . ', ' . $booking->availableSeats() . ' seats available. Date: ' . $m->getMovieDate() . ' @ ' . $m->getMovieTime());
            $booking->printBookingTable()->confirmBooking();


            if ($booking->getBookingId()) {
                $this->printMovieWelcomeScreen('Movie: ' . $m->getMovieTitle() . ',  Date: ' . $m->getMovieDate() . ' @ ' . $m->getMovieTime());
                $booking->printBookingDetails();
            }

        } else {
            $this->printInit();
        }
    }

    public function listBookings(){
        $this->printMovieWelcomeScreen('Booking List');
        $booking = new bookings($this);
        $booking->getBookingList();

        $this->waitForReturnToMenu();
    }

    public function deleteBooking() {
        $this->printMovieWelcomeScreen('Booking List');
        $booking = new bookings($this);
        $booking->getBookingList();
        output::message('What booking ID would you like to remove?', 5);
        $bookingId = (new input())->getIntResponse(0,99,true)->getInputData();

        if( $bookingId ) {
            $booking->setBookingId($bookingId);
            $booking->loadBookingData();

            $booking->printBookingDetails();
            output::blankRow();
            output::message('To confirm type: Y');
            $response = (new input())->getStringResponse()->getInputData();

            if( strtolower($response) == 'y' ) {
                $booking->deleteBookingFromDatabase();

            }
            $this->printInit();
        } else {
            $this->printInit();
        }
    }

}
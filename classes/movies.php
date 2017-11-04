<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 03/11/17
 * Time: 01:01
 */

namespace CBS;


/**
 * Class movies
 * @package CBS
 */
class movies extends menu
{

    /**
     * @var
     */
    private $movieTitle;
    /**
     * @var
     */
    private $movieDate;
    /**
     * @var
     */
    private $movieTime;
    /**
     * @var
     */
    private $movieObject;
    /**
     * @var
     */
    private $movieId;
    /**
     * @var
     */
    private $movieSeats;
    /**
     * @var
     */
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


    /**
     * Print welcome header and append message (UI Only)
     * @param $message
     */
    public function printMovieWelcomeScreen($message){
        $this->printWelcomeScreen();
        output::message($message,'center');
        output::blankRow();
    }

    /**
     * Print movie adding screen (UI Only)
     */
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

    /**
     * Add a movie (CL Only)
     */
    public function addMovieCLI(){

        global $argv;

        if( !isset($argv[4]) ) {
            output::responseError('Please add all parameters');
            output::message('Examples:');
            output::message('addmovie "movie title" "movie date" "movie time"');
            output::message('addmovie "The Avengers" "next saturday" "12:30"');
            exit;
        }

        $movieTitle = trim($argv[2]);
        $movieDate = trim($argv[3]);
        $movieTime = trim($argv[4]);

        if( empty($movieTitle) ) {
            output::responseError('Movie title cannot be empty');
            exit;
        }


        $input = new input();

        $movieDate = str_replace('/','-',$movieDate);

        if( !$input->validateDate($movieDate) ) {
            output::responseError('Invalid date format');
            output::responseError('Use "next tuesday" or "24/10/2018"');
            exit;
        }

        if( !$input->validateTime($movieTime) ) {
            output::responseError('Please format the time as HH:MM');
            exit;
        }



        $this->setMovieTitle($movieTitle);
        $this->setMovieDate($movieDate);
        $this->setMovieTime($movieTime);



        $this->addMovieToDatabase();
        $this->loadMovieData();

        output::message('Great, Movie Added');
        output::message('Movie ID: ' . $this->getMovieId());
        output::message('Movie Title: ' . $this->getMovieTitle());
        output::message('Movie Air Date: ' . $this->getMovieDate());
        output::message('Movie Air Time: ' . $this->getMovieTime());

    }

    /**
     * Print confirmation screen for movies (UI Only)
     * @return mixed
     */
    private function confirmMovie() {
        $this->printMovieWelcomeScreen('Please confirm the following details are correct:');
        output::message('Movie Title: ' . $this->getMovieTitle(),5);
        output::message('Movie Air Date: ' . $this->getMovieDate(),5);
        output::message('Movie Air Time: ' . $this->getMovieTime(),5);

        output::blankRow();
        output::message('To confirm type: Y', 5);
        $response = (new input())->getStringResponse()->getInputData();
        return $response;

    }

    /**
     * Print confirmation of movie being added (UI Only)
     */
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


    /**
     * Print confirmation of movie being deleted (UI Only)
     */
    private function deleteMovieConfirm() {

        $response = $this->confirmMovie();

        if( strtolower($response) == 'y' ) {
            $this->deleteMovieFromDatabase();

            $this->printMovieWelcomeScreen('The following movie has been removed:');
            output::message('Movie Title: ' . $this->getMovieTitle(),5);
            output::message('Movie Air Date: ' . $this->getMovieDate(),5);
            output::message('Movie Air Time: ' . $this->getMovieTime(),5);

            output::blankRow();
            $this->waitForReturnToMenu();
        }

        $this->printInit();
    }


    /**
     * Add movie to the database
     * @return bool
     */
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

    /**
     * Delete movie from the database
     * @return bool
     */
    private function deleteMovieFromDatabase() {
        \R::trash($this->getMovieObject());
        return true;
    }

    /**
     * Load movie data into the instance
     * @param null $id
     * @return bool
     */
    public function loadMovieData($id = null) {
        if( $id ) {
            $this->setMovieId($id);
        }

        if( !$this->getMovieId() ) {
            output::responseError('No movie ID set');
            return false;
        }

        $obj = \R::load('movie', $this->getMovieId());

        if( !$obj->getID() ) {
            output::responseError('No movie found');
            return false;
        }

        $this->setMovieTitle($obj->movieTitle);
        $this->setMovieDate(date('d/m/Y',$obj->movieShowing));
        $this->setMovieTime(date('H:i',$obj->movieShowing));
        $this->setMovieSeats($obj->movieSeats);
        $this->setMovieObject($obj);

        $bookings = new bookings($this);

        $this->setBookingData($bookings);

        return true;

    }

    /**
     * Print details of the movie (UI Only)
     */
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

    /**
     * Delete a movie (UI Only)
     */
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

    /**
     * Delete a movie (CL Only)
     */
    public function deleteMovieCLI() {

        global $argv;

        if( !isset($argv[2]) ) {
            output::responseError('No movie ID set');
            exit;
        }

        $movieId = $argv[2];

        if( !$this->loadMovieData($movieId) ) {
            exit;
        }
        $this->deleteMovieFromDatabase();

        output::message('Movie: ' . $this->movieTitle . ' was deleted');
    }

    /**
     * Print a list of movies
     */
    public function getMovieList() {
        $obj = \R::findAll('movie', ' ORDER BY `movie_showing` ASC ');

        $str = 'ID: %s | %s @ %s | %s';

        if( $obj ) {
            foreach ($obj as $item) {
                $m = new movies();
                $m->loadMovieData($item->getId());
                output::message(sprintf($str, $m->getMovieId(), $m->getMovieDate(), $m->getMovieTime(), $m->getMovieTitle()), 5);
            }
        } else {
            output::message('No movies available to view.', 5);
        }
        output::blankRow();
    }

    /**
     * List all movies (UI Only)
     */
    public function listMovies(){
        $this->printMovieWelcomeScreen('Movie List');
        $this->getMovieList();

        $this->waitForReturnToMenu();
    }

    /**
     * Return to menu prompt (UI Only)
     */
    public function waitForReturnToMenu() {
        output::blankRow();
        output::message('Press return to load menu', 5);
        (new input())->getStringResponse(true)->getInputData();

        $this->printInit();
    }

    /**
     * Print list of movies to view details of
     */
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

    /**
     * Add a booking (UI Only)
     */
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
                $booking->printBookingDetails();
            }

        } else {
            $this->printInit();
        }
    }

    /**
     * Add a booking (CL Only)
     */
    public function addBookingCLI() {

        global $argv;

        if( !isset($argv[4]) ) {
            output::responseError('Please add all parameters');
            output::message('Examples:');
            output::message('addbooking "movie id" "customer name" "seats required"');
            output::message('addbooking 3 "John Smith" 1');
            exit;
        }

        $movieId = trim($argv[2]);
        $customerName = trim($argv[3]);
        $seatsRequired = trim($argv[4]);

        if( !$this->loadMovieData($movieId) ) {
            exit;
        }

        if( !$customerName ) {
            output::responseError('Customer name cannot be blank');
            exit;
        }

        if( !is_numeric($seatsRequired) ) {
            output::responseError('Number of seats must be a valid number');
            exit;
        }

        $seatsAvailable = $this->getBookingData()->availableSeats();

        if( $seatsAvailable < $seatsRequired ) {
            output::responseError(sprintf('There are only %d seats available', $seatsAvailable));
            exit;
        }

        $allocatedSeats = $this->getBookingData()->getAllocatedSeatsForMovie();

        $seatsForThisBooking = [];

        $loopedSeats = 1;
        $bookingSeatsAllocated = 0;

        while($loopedSeats <= 10 && $bookingSeatsAllocated != $seatsRequired){

            if( !in_array($loopedSeats, $allocatedSeats) ) {
                $seatsForThisBooking[] = $loopedSeats;
                $bookingSeatsAllocated++;
            }


            $loopedSeats++;
        }

        $this->getBookingData()->setBookingSeats($seatsForThisBooking);
        $this->getBookingData()->setCustomerName($customerName);

        $bookingId = $this->getBookingData()->confirmBooking();


        output::message('Great, booking confirmed');
        output::message('Booking ID: ' . $bookingId);
        output::message('Movie: ' . $this->getMovieTitle());
        output::message('Customer Name: ' . $customerName);
        output::message('Seats Allocated: ' . implode(',', $seatsForThisBooking));

    }

    /**
     * List bookings (UI Only)
     */
    public function listBookings(){
        $this->printMovieWelcomeScreen('Booking List');

        $this->getMovieList();

        output::message('What movie ID would you like to view bookings for?', 5);
        output::message('Hint: type 0 to display all bookings',5);
        $movieId = (new input())->getIntResponse(0,99)->getInputData();


        if( $movieId == 0 ) {
            $this->printMovieWelcomeScreen('Booking List: Viewing All');
        } else {
            $m = new movies();
            $m->loadMovieData($movieId);
            $this->printMovieWelcomeScreen('Booking List: ' . $m->getMovieTitle());
        }

        $booking = new bookings($this);
        $booking->getBookingList($movieId);


        $this->waitForReturnToMenu();
    }

    /**
     * List bookings (CL Only)
     */
    public function listBookingsCLI() {

        global $argv;

        $movieId = null;
        if( isset($argv[2]) ) {
            $movieId = trim($argv[2]);
        }

        $booking = new bookings($this);
        $booking->getBookingList($movieId);
    }

    /**
     * Delete a booking (UI Only)
     */
    public function deleteBooking() {
        $this->printMovieWelcomeScreen('Booking List');
        $booking = new bookings($this);
        $booking->getBookingList();
        output::message('What booking ID would you like to remove?', 5);
        $bookingId = (new input())->getIntResponse(0,99,true)->getInputData();

        if( $bookingId ) {
            $booking->setBookingId($bookingId);
            $booking->loadBookingData();

            $booking->printBookingDetails(false);
            output::blankRow();
            output::message('To confirm type: Y',5);
            $response = (new input())->getStringResponse()->getInputData();

            if( strtolower($response) == 'y' ) {
                $booking->deleteBookingFromDatabase();

                $this->printMovieWelcomeScreen('The following booking has been removed:');

                output::message('Booking ID: ' . $booking->getBookingId(), 5 );
                output::message('Customer Name: ' . $booking->getCustomerName(), 5 );

                $this->waitForReturnToMenu();

            }
            $this->printInit();
        } else {
            $this->printInit();
        }
    }

    /**
     * Delete a booking (CL Only)
     */
    public function deleteBookingCLI() {

        global $argv;

        if( !isset($argv[2]) ) {
            output::responseError('No booking ID set');
            exit;
        }

        $bookingId = $argv[2];
        $booking = new bookings($this);
        $booking->setBookingId($bookingId);
        if(!$booking->loadBookingData()) {
            exit;
        }

        $booking->deleteBookingFromDatabase();

        output::message('Booking: ' . $booking->getCustomerName() . ' was deleted');
    }



}
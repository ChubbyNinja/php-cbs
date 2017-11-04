<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 03/11/2017
 * Time: 10:17
 */

namespace CBS;


/**
 * Class bookings
 * @package CBS
 */
class bookings
{
    /**
     * @var
     */
    private $movieData;
    /**
     * @var
     */
    private $totalBookings;
    /**
     * @var
     */
    private $allocatedSeats;
    /**
     * @var
     */
    private $bookingId;
    /**
     * @var
     */
    private $customerName;
    /**
     * @var
     */
    private $bookingSeats;
    /**
     * @var
     */
    private $bookingData;

    /**
     * bookings constructor.
     * Load movie instance, count bookings and allocated seats
     * @param $movie
     */
    public function __construct($movie)
    {
        $this->setMovieData($movie);
        $this->countBookings();
        $this->countAllocatedSeats();
    }

    /**
     * @return mixed
     */
    public function getMovieData()
    {
        return $this->movieData;
    }

    /**
     * @param mixed $movie
     */
    public function setMovieData($movie)
    {
        $this->movieData = $movie;
    }

    /**
     * @return mixed
     */
    public function getTotalBookings()
    {
        return $this->totalBookings;
    }

    /**
     * @param mixed $totalBookings
     */
    public function setTotalBookings($totalBookings)
    {
        $this->totalBookings = $totalBookings;
    }

    /**
     * @return mixed
     */
    public function getAllocatedSeats()
    {
        return $this->allocatedSeats;
    }

    /**
     * @param mixed $allocatedSeats
     */
    public function setAllocatedSeats($allocatedSeats)
    {
        $this->allocatedSeats = $allocatedSeats;
    }

    /**
     * @return mixed
     */
    public function getBookingId()
    {
        return $this->bookingId;
    }

    /**
     * @param mixed $bookingId
     */
    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param mixed $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return mixed
     */
    public function getBookingSeats()
    {
        return $this->bookingSeats;
    }

    /**
     * @param mixed $bookingSeats
     */
    public function setBookingSeats($bookingSeats)
    {
        $this->bookingSeats = $bookingSeats;
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
     * Count bookings for the movie instance
     */
    private function countBookings(){
        $totalBookings = \R::count('booking', ' movie_id = ? ', [$this->getMovieData()->getMovieId()]);
        $this->setTotalBookings($totalBookings);
    }


    /**
     * Calculate how many seats are available for this movie
     * @return mixed
     */
    public function availableSeats() {
        $totalSeats = $this->getMovieData()->getMovieSeats();
        $allocatedSeats = $this->getAllocatedSeats();

        return $totalSeats - $allocatedSeats;
    }

    /**
     * Calculate how many seats have been allocated for this movie
     */
    private function countAllocatedSeats() {
        $allocatedSeats = \R::count('seat', ' movie_id = ? ', [$this->getMovieData()->getMovieId()]);
        $this->setAllocatedSeats($allocatedSeats);
    }

    /**
     * Return the seat numbers for a specific booking
     * @param $id
     * @return array
     */
    private function getAllocatedSeatsForBooking($id ) {
        $allocatedSeats = \R::getAll("SELECT `seat_number` FROM seat WHERE booking_id = ? ", [$id]);
        return array_column($allocatedSeats, 'seat_number');
    }

    /**
     * Return the seat numbers for a specific movie
     * @return array
     */
    public function getAllocatedSeatsForMovie( ) {
        $allocatedSeats = \R::getAll("SELECT `seat_number` FROM seat WHERE movie_id = ? ", [$this->getMovieData()->getMovieId()]);
        return array_column($allocatedSeats, 'seat_number');
    }

    /**
     * Print the booking table (UI only)
     * @return $this
     */
    public function printBookingTable() {

        $seatsTaken = $this->getAllocatedSeatsForMovie();

        $s = [];
        $i=1;
        while($i <= 10){
            $s[$i] = $this->printSeatNumberForTable($i,$seatsTaken);
            $i++;
        }

        output::message('                           [/\/\/\/\/\/\/\/\]',5);
        output::blankRow();
        output::message('ROW A |            [' . $s[1] . '][' . $s[2] . '] |##| [' . $s[3] . '][' . $s[4] . ']',5);
        output::message('ROW B |     [' . $s[5] . '][' . $s[6] . '][' . $s[7] . '] |##| [' . $s[8] . '][' . $s[9] . '][' . $s[10] . ']',5);
        output::blankRow();

        output::message('Which seats would you like to book?', 5);
        output::message('Hint: Provide seat numbers separated by a comma', 5);
        $seats = (new input())->getStringResponse(true)->getInputData();

        if(!$seats){
            $this->getMovieData()->printInit();
        }
        $seatNumbers = explode(',', $seats);

        if( !$this->checkSeatsAvailable($seatNumbers) ){

            output::responseError('Seats not available');
            $this->getMovieData()->waitForReturnToMenu();

        } else {

            output::blankRow();
            output::message('Customer Name?', 5);
            $customerName = (new input())->getStringResponse(true)->getInputData();

            $this->setBookingSeats($seatNumbers);
            $this->setCustomerName($customerName);

        }

        return $this;

    }


    /**
     * Check if the seat number is available (UI Only)
     * @param $seats
     * @return bool
     */
    private function checkSeatsAvailable($seats){


        $taken = $this->getAllocatedSeatsForMovie();
        $error = false;
        foreach($seats as $seat) {
            if( in_array($seat, $taken) ) {
                $error = true;
            }
        }

        return ($error) ? false : true;

    }

    /**
     * Print seat number if available, and x if not (UI Only)
     * @param $seatNumber
     * @param $seatsTaken
     * @return string
     */
    private function printSeatNumberForTable($seatNumber, $seatsTaken){

        if( in_array($seatNumber, $seatsTaken) ) {
            return '  x  ';
        } else {
            return str_pad($seatNumber,5,' ', STR_PAD_BOTH);
        }


    }

    /**
     * Add the booking to the database and allocate the seats
     * @return int|string
     */
    public function confirmBooking(){

        $seatNumbers = $this->getBookingSeats();

        $obj = \R::dispense('booking');
        $obj->movieId = $this->getMovieData()->getMovieId();
        $obj->allocatedSeats = count($seatNumbers);
        $obj->customerName = $this->getCustomerName();
        $bookingId = \R::store($obj);

        $this->setBookingId($bookingId);

        foreach ($seatNumbers as $number ){
            $obj = \R::dispense('seat');
            $obj->movieId = $this->getMovieData()->getMovieId();
            $obj->bookingId = $this->getBookingId();
            $obj->seatNumber = $number;
            \R::store($obj);
        }

        $this->setBookingId($bookingId);
        $this->loadBookingData($bookingId);

        return $bookingId;
    }

    /**
     * load the booking data
     * @param null $id
     * @return bool
     */
    public function loadBookingData($id = null) {
        if( $id ) {
            $this->setBookingId($id);
        }

        if( !$this->getBookingId() ) {
            output::responseError('No booking ID set');
            return false;
        }

        $obj = \R::load('booking', $this->getBookingId());

        if( !$obj->getID() ) {
            output::responseError('No booking found');
            return false;
        }

        $this->setCustomerName($obj->customerName);
        $this->setBookingData($obj);

        return true;

    }

    /**
     * Display the booking details (UI Only)
     * @param bool $menu
     */
    public function printBookingDetails($menu = true) {

        $m = new movies();

        $m->loadMovieData($this->getBookingData()->movieId);

        $seats = $this->getAllocatedSeatsForBooking($this->getBookingId());

        $this->getMovieData()->printMovieWelcomeScreen('Movie: ' . $m->getMovieTitle() . ',  Date: ' . $m->getMovieDate() . ' @ ' . $m->getMovieTime());

        output::message('Booking ID: ' . $this->getBookingId(), 5 );
        output::message('Customer Name: ' . $this->getCustomerName(), 5 );

        output::message('Seats Allocated: ' . implode(', ', $seats), 5);

        if( $menu ) {
            $this->getMovieData()->waitForReturnToMenu();
        }

    }

    /**
     * Display the list of bookings
     * @param null $movieId
     */
    public function getBookingList($movieId = null){

        if( $movieId && $movieId > 0 ) {
            $obj = \R::findAll('booking', ' WHERE movie_id = ? ', [$movieId]);
        } else {
            $obj = \R::findAll('booking');
        }

        $str = 'ID: %s | %s | %s | seats: %s';

        if( $obj ) {
            foreach ($obj as $item) {

                $seats = $this->getAllocatedSeatsForBooking( $item->getId() );
                $m = new movies();
                $m->loadMovieData($item->movieId);

                $movieString = $m->getMovieTitle() . ', Date: ' . $m->getMovieDate() . ' @ ' . $m->getMovieTime();

                output::message(sprintf($str, $item->getId(), $movieString, $item->customerName,  implode(', ', $seats) ), 5);
            }
        } else {
            output::message('No bookings available to view.', 5);
        }
        output::blankRow();
    }


    /**
     * Remove a booking
     * @return bool
     */
    public function deleteBookingFromDatabase() {

        \R::exec("DELETE FROM seat WHERE booking_id = ? ",[$this->getBookingId()]);
        \R::trash($this->getBookingData());
        return true;
    }
}
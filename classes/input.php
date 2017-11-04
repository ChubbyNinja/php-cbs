<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 03/11/17
 * Time: 00:35
 */

namespace CBS;


/**
 * Class input
 * @package CBS
 */
class input
{

    /**
     * @var
     */
    private $inputData;

    /**
     * @return mixed
     */
    public function getInputData()
    {
        return $this->inputData;
    }

    /**
     * @param mixed $inputData
     */
    public function setInputData($inputData)
    {
        $this->inputData = $inputData;
    }


    /**
     * Wait for the user to input some data (UI Only)
     * @return string
     */
    private function getResponse() {
        $handle = fopen ("php://stdin","r");
        $response = fgets($handle);
        fclose($handle);

        return $response;
    }

    /**
     * Request a number from the user
     * @param $min
     * @param $max
     * @param bool $cancel
     * @return $this|input
     */
    public function getIntResponse($min, $max, $cancel = false){
        $this->userInput($cancel);

        $response = $this->getResponse();
        $result = trim($response);

        if( $cancel && strtolower($result) == 'n' ){
            $this->setInputData(false);
            return $this;
        }

        preg_match("/[$min-$max]/", $result, $matched);
        if( !$matched ) {
            output::responseError('Please type a numerical value');
            return $this->getIntResponse($min,$max,$cancel);
        }

        $this->setInputData($result);
        return $this;

    }

    /**
     * Request text from the user
     * @param bool $blank
     * @param bool $cancel
     * @return $this|input
     */
    public function getStringResponse($blank = false, $cancel = false){
        $this->userInput($cancel);

        $response = $this->getResponse();

        $result = trim($response);

        if( empty($result) && !$blank ) {
            output::responseError('Value cannot be blank');
            return $this->getStringResponse($cancel);
        }

        $this->setInputData($result);
        return $this;

    }


    /**
     * Request a date from the user
     * @param bool $cancel
     * @return $this|input
     */
    public function getDateResponse($cancel = false){
        $this->userInput($cancel);

        $response = $this->getResponse();

        $result = trim($response);
        $result = str_replace('/','-',$result);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return $this->getDateResponse($cancel);
        }


        if( !$this->validateDate($result) ) {
            output::responseError('Invalid date format');
            return $this->getDateResponse($cancel);
        }

        $formatted = date('d/m/Y', strtotime($result) );

        $this->setInputData($formatted);
        return $this;


    }


    /**
     * Request a time from the user
     * @return $this|input
     */
    public function getTimeResponse(){
        $this->userInput();

        $response = $this->getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return $this->getDateResponse();
        }


        if( !$this->validateTime($result) ) {
            output::responseError('Please format the time as HH:MM');
            return $this->getTimeResponse();
        }

        $this->setInputData($result);
        return $this;


    }

    /**
     * Validate date format
     * @param $input
     * @return false|int
     */
    public function validateDate($input) {
        $unixTimestamp = strtotime($input);

        return $unixTimestamp;
    }

    /**
     * Validate time format
     * @param $input
     * @return mixed
     */
    public function validateTime($input) {
        preg_match("/^(?:[01][0-9]|2[0-3]):[0-5][0-9]/", $input, $matched);
        return $matched;
    }

    /**
     * Prompt for user
     * @param bool $cancel
     */
    private function userInput($cancel = false) {
        if( $cancel ) {
            output::message('Type N to cancel',5);
        }
        echo '     : ';
    }

}
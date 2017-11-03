<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 03/11/17
 * Time: 00:35
 */

namespace CBS;


class input
{

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




    private function getResponse() {
        $handle = fopen ("php://stdin","r");
        $response = fgets($handle);
        fclose($handle);

        return $response;
    }

    public function getIntResponse($min,$max, $cancel = false){
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


    public function getDateResponse($cancel = false){
        $this->userInput($cancel);

        $response = $this->getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return $this->getDateResponse($cancel);
        }

        $unixTimestamp = strtotime($result);

        if( !$unixTimestamp ) {
            output::responseError('Invalid date format');
            return $this->getDateResponse($cancel);
        }

        $formatted = date('d/m/Y', strtotime($result) );

        $this->setInputData($formatted);
        return $this;


    }


    public function getTimeResponse(){
        $this->userInput();

        $response = $this->getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return $this->getDateResponse();
        }

        preg_match("/^(?:[01][0-9]|2[0-3]):[0-5][0-9]/", $result, $matched);
        if( !$matched ) {
            output::responseError('Please format the time as HH:MM');
            return $this->getTimeResponse();
        }

        $this->setInputData($result);
        return $this;


    }

    private function userInput($cancel = false) {
        if( $cancel ) {
            output::message('Type N to cancel',5);
        }
        echo '     : ';
    }

}
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

    private function getResponse() {
        $handle = fopen ("php://stdin","r");
        $response = fgets($handle);
        fclose($handle);

        return $response;
    }

    public static function getIntResponse($min,$max){
        self::userInput();

        $response = self::getResponse();

        preg_match("/[$min-$max]/", $response, $result);
        if( !$result ) {
            output::responseError('Please type a numerical value');
            return self::getIntResponse($min,$max);
        }

        return $response;

    }

    public static function getStringResponse(){

        self::userInput();

        $response = self::getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return self::getStringResponse();
        }

        return $result;

    }


    public static function getDateResponse(){

        self::userInput();

        $response = self::getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return self::getDateResponse();
        }

        $unixTimestamp = strtotime($result);

        if( !$unixTimestamp ) {
            output::responseError('Invalid date format');
            return self::getDateResponse();
        }

        $formatted = date('d/m/Y', strtotime($result) );

        return $formatted;

    }


    public static function getTimeResponse(){

        self::userInput();

        $response = self::getResponse();

        $result = trim($response);

        if( empty($result) ) {
            output::responseError('Value cannot be blank');
            return self::getDateResponse();
        }

        preg_match("/^(?:[01][0-9]|2[0-3]):[0-5][0-9]/", $response, $result);
        if( !$result ) {
            output::responseError('Please format the time as HH:MM');
            return self::getTimeResponse();
        }

        return $response;

    }

    private function userInput() {
        echo '     : ';
    }

}
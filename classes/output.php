<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:52
 */

namespace CBS;


/**
 * Class output
 * @package CBS
 */
class output
{

    /**
     * Output text to terminal
     * @param $message
     * @param string $align
     * @param null $colour
     */
    public static function message($message, $align = 'left', $colour = null ) {

        if( is_numeric($align) ) {
            $message = self::addLeftPaddingToMessage($message,$align);
        } elseif($align == 'center') {
            $message = self::centerAlignMessage($message);
        }

        $message = self::addColourToMessage($message,$colour);


        echo $message . "\n";
    }

    /**
     * Output blank row to terminal
     */
    public static function blankRow() {
        self::message(' ');
    }

    /**
     * Output line break to terminal
     * @param string $char
     * @param string $width
     */
    public static function lineBreak($char = '~', $width = 'full') {

        $output = str_repeat($char, self::WidthTextToNum($width));

        self::message($output,'center');

    }

    /**
     * Convert text based width to a numerical value to pad text
     * @param $width
     * @return float|int|string
     */
    private static function widthTextToNum($width){

        switch( $width ) {
            case 'full':
                return self::getWidth();
                break;

            case 'half':
                return floor(self::getWidth()/2);
                break;

            default:
                return self::getWidth();
        }

    }

    /**
     * Add colour to outputted message (UNIX Only)
     * @param $message
     * @param $colour
     * @return string
     */
    private static function addColourToMessage($message, $colour) {

        if( is_null($colour) || app::isWIN() ){
            return $message;
        }

        switch ($colour) {
            case 'white':
                $colourCode = '1;37';
                break;
            case 'red':
                $colourCode = '0;31';
                break;

            default:
                $colourCode = '1;37';
        }



        return sprintf("\033[%sm%s\033[0m",$colourCode,$message);
    }

    /**
     * Detect width of terminal to help with centering (UNIX Only, WIN=80)
     * @return int|string
     */
    public static function getWidth(){

        if( app::isWIN() ) {
            return 80;
        }

        return exec('tput cols');
    }

    /**
     * Center text based on terminal width and message length
     * @param $message
     * @return string
     */
    private static function centerAlignMessage($message) {
        $messageLength = self::getMessageLength($message);

        if( $messageLength >= self::getWidth() ) {
            return $message;
        }
        $leftPadding = floor((self::getWidth() - $messageLength) / 2);


        return str_pad($message, $leftPadding + $messageLength, ' ', STR_PAD_LEFT);
    }

    /**
     * Calculate message length
     * @param $message
     * @return int
     */
    private static function getMessageLength($message){
        return strlen($message);
    }

    /**
     * Add left padding to message
     * @param $message
     * @param $leftPadding
     * @return string
     */
    private static function addLeftPaddingToMessage($message, $leftPadding) {
        $messageLength = self::getMessageLength($message);
        return str_pad($message, $leftPadding + $messageLength, ' ', STR_PAD_LEFT);
    }

    /**
     * Output error message to terminal
     * @param $message
     */
    public static function responseError($message) {
        self::message('!!'.$message,5,'red');
    }

    /**
     * Clear terminal
     */
    public static function clearScreen(){
        if( app::isWIN() ) {
            echo str_repeat("\n", 200);
            //system('clr');
        } else {
            system('clear');
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chubbyninja
 * Date: 02/11/17
 * Time: 23:52
 */

namespace CBS;


class output
{
    static $width;

    function __construct()
    {
        //echo exec('tput cols');
    }

    public static function message( $message, $align = 'left', $colour = null ) {

        if( is_numeric($align) ) {
            $message = self::addLeftPaddingToMessage($message,$align);
        } elseif($align == 'center') {
            $message = self::centerAlignMessage($message);
        }

        $message = self::addColourToMessage($message,$colour);


        echo $message . "\n";
    }

    public static function blankRow() {
        self::message(' ');
    }

    public static function lineBreak($char = '~', $width = 'full') {

        $output = str_repeat($char, self::WidthTextToNum($width));

        self::message($output,'center');

    }

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

    private static function addColourToMessage($message,$colour) {

        if( is_null($colour) ){
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

    public static function getWidth(){
        return 80;
    }

    private static function centerAlignMessage($message) {
        $messageLength = self::getMessagelength($message);

        if( $messageLength >= self::getWidth() ) {
            return $message;
        }
        $leftPadding = floor((self::getWidth() - $messageLength) / 2);


        return str_pad($message, $leftPadding + $messageLength, ' ', STR_PAD_LEFT);
    }

    private static function getMessagelength($message){
        return strlen($message);
    }

    private static function addLeftPaddingToMessage($message,$leftPadding) {
        $messageLength = self::getMessagelength($message);
        return str_pad($message, $leftPadding + $messageLength, ' ', STR_PAD_LEFT);
    }

    public static function responseError($message) {
        self::message('!!'.$message,0,'red');
    }

    public static function clearScreen(){
        system('clear');
    }
}
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/5/15
     * Time: 8:47 AM
     */
    class Console {

        /**
         * get console window width in columns
         *
         * @return int
         */

        public static function getConsoleWidth() {

            $cols = (int)exec('tput cols');

            if(defined('MANGA_SCRAPPER_MAX_CONSOLE_WINDOW_WIDTH') &&
                MANGA_SCRAPPER_MAX_CONSOLE_WINDOW_WIDTH > 0 &&
                $cols > MANGA_SCRAPPER_MAX_CONSOLE_WINDOW_WIDTH) {
                $cols = MANGA_SCRAPPER_MAX_CONSOLE_WINDOW_WIDTH;
            }

            return $cols;

        }

        /**
         * Number of lines to print on console
         *
         * @param int $lines
         */

        public static function emptyLines($lines = 1) {

            $lines = (int)$lines;

            if($lines < 1) {
                $lines = 1;
            }

            echo str_repeat(PHP_EOL,$lines);

        }

        /**
         * Prints some text on console
         *
         * @param string $message The text string
         * @param int    $tabs Number of tabs to prepend
         * @param string $color Text color
         */

        public static function text($message = '',$tabs = 0,$color = '') {

            $tabs = (int)$tabs;

            if($tabs < 0) {
                $tabs = 0;
            }

            $color = trim($color);

            echo str_repeat("\t",$tabs);

            if($color != '') {
                echo ConsoleColors::coloredText($message,$color,true);
            } else {
                echo $message;
            }

        }

        /**
         * generates a seperator line by repeating the given character upto console window width
         *
         * @param string $char
         */

        public static function seperatorLine($char = '') {

            $char = trim($char);

            if($char == '') {
                $char = MANGA_SCRAPPER_SEPERATOR_LINE_CHAR;
            }

            $message = str_repeat($char,self::getConsoleWidth());

            echo ConsoleColors::coloredText($message,MANGA_SCRAPPER_SEPERATOR_LINE_COLOR);
            self::emptyLines(1);
        }

    }
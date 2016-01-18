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
         * @param int    $tabs    Number of tabs to prepend
         * @param string $color   Text color
         * @param bool   $return
         */

        public static function text($message = '',$tabs = 0,$color = '',$return = false) {

            $tabs = (int)$tabs;

            if($tabs < 0) {
                $tabs = 0;
            }

            $color = trim($color);

            echo str_repeat(MANGA_SCRAPPER_TAB_STR,$tabs);



            if($color != '') {
                $message = ConsoleColors::coloredText($message,$color,true);
            }

            if($return) {
                return $message;
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

        /**
         * Break a single line of text to span across multiple lines with some indenting text
         *
         * @param string     $str
         * @param string     $indentText
         * @param string     $color
         * @param bool|FALSE $skipFirstLineIndent
         */

        public static function writeMultiline($str = '',$indentText = '',$color = '',$skipFirstLineIndent = false) {

            $str = wordwrap($str,self::getConsoleWidth() - strlen($indentText));

            $parts = explode("\n",$str);

            $color = trim($color);

            $i = 0;

            foreach($parts as $p) {

                if($skipFirstLineIndent && $i == 0) {
                    self::text($p,0,$color);
                } else {
                    self::text($indentText.$p,0,$color);
                }

                self::emptyLines(1);

                $i += 1;

            }

        }

    }
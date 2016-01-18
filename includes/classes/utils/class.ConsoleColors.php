<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 9/2/15
     * Time: 10:34 PM
     */


    class ConsoleColors {

        # Reset
        const RESET_COLOR = '[0m';       # Text Reset

        # Regular Colors
        const COLOR_BLACK  = '[0;30m';        # Black
        const COLOR_RED    = '[0;31m';          # Red
        const COLOR_GREEN  = '[0;32m';        # Green
        const COLOR_YELLOW = '[0;33m';       # Yellow
        const COLOR_BLUE   = '[0;34m';         # Blue
        const COLOR_PURPLE = '[0;35m';       # Purple
        const COLOR_CYAN   = '[0;36m';         # Cyan
        const COLOR_WHITE  = '[0;37m';        # White

        # Bold
        const BOLD_BLACK  = '[1;30m';       # Black
        const BOLD_RED    = '[1;31m';         # Red
        const BOLD_GREEN  = '[1;32m';       # Green
        const BOLD_YELLOW = '[1;33m';      # Yellow
        const BOLD_BLUE   = '[1;34m';        # Blue
        const BOLD_PURPLE = '[1;35m';      # Purple
        const BOLD_CYAN   = '[1;36m';        # Cyan
        const BOLD_WHITE  = '[1;37m';       # White

        # Underline
        const UNDERLINE_BLACK  = '[4;30m';       # Black
        const UNDERLINE_RED    = '[4;31m';         # Red
        const UNDERLINE_GREEN  = '[4;32m';       # Green
        const UNDERLINE_YELLOW = '[4;33m';      # Yellow
        const UNDERLINE_BLUE   = '[4;34m';        # Blue
        const UNDERLINE_PURPLE = '[4;35m';      # Purple
        const UNDERLINE_CYAN   = '[4;36m';        # Cyan
        const UNDERLINE_WHITE  = '[4;37m';       # White

        # Background
        const BACKGROUND_BLACK  = '[40m';       # Black
        const BACKGROUND_RED    = '[41m';         # Red
        const BACKGROUND_GREEN  = '[42m';       # Green
        const BACKGROUND_YELLOW = '[43m';      # Yellow
        const BACKGROUND_BLUE   = '[44m';        # Blue
        const BACKGROUND_PURPLE = '[45m';      # Purple
        const BACKGROUND_CYAN   = '[46m';        # Cyan
        const BACKGROUND_WHITE  = '[47m';       # White

        # High Intensity
        const INTENSE_BLACK  = '[0;90m';       # Black
        const INTENSE_RED    = '[0;91m';         # Red
        const INTENSE_GREEN  = '[0;92m';       # Green
        const INTENSE_YELLOW = '[0;93m';      # Yellow
        const INTENSE_BLUE   = '[0;94m';        # Blue
        const INTENSE_PURPLE = '[0;95m';      # Purple
        const INTENSE_CYAN   = '[0;96m';        # Cyan
        const INTENSE_WHITE  = '[0;97m';       # White

        # Bold High Intensity
        const BOLD_INTENSE_BLACK  = '[1;90m';      # Black
        const BOLD_INTENSE_RED    = '[1;91m';        # Red
        const BOLD_INTENSE_GREEN  = '[1;92m';      # Green
        const BOLD_INTENSE_YELLOW = '[1;93m';     # Yellow
        const BOLD_INTENSE_BLUE   = '[1;94m';       # Blue
        const BOLD_INTENSE_PURPLE = '[1;95m';     # Purple
        const BOLD_INTENSE_CYAN   = '[1;96m';       # Cyan
        const BOLD_INTENSE_WHITE  = '[1;97m';      # White

        # High Intensity backgrounds
        const INTENSE_BACKGROUND_BLACK  = '[0;100m';   # Black
        const INTENSE_BACKGROUND_RED    = '[0;101m';     # Red
        const INTENSE_BACKGROUND_GREEN  = '[0;102m';   # Green
        const INTENSE_BACKGROUND_YELLOW = '[0;103m';  # Yellow
        const INTENSE_BACKGROUND_BLUE   = '[0;104m';    # Blue
        const INTENSE_BACKGROUND_PURPLE = '[0;105m';  # Purple
        const INTENSE_BACKGROUND_CYAN   = '[0;106m';    # Cyan
        const INTENSE_BACKGROUND_WHITE  = '[0;107m';   # White

        /**
         * Get color formatted text for console
         *
         * @param string    $text
         * @param string    $color
         * @param bool|TRUE $reset
         *
         * @return string
         */

        public static function coloredText($text = '',$color = self::COLOR_WHITE,$reset = true) {

            return chr(27).$color.$text.chr(27).self::RESET_COLOR;

        }

        static function getConstants() {
            $oClass = new \ReflectionClass(__CLASS__);
            return $oClass->getConstants();
        }

    }
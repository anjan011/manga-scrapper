<?php

    require_once('functions/functions.php');

    define('MANGA_ROOT_DIR',dirname(__FILE__).'/');

    define('MANGA_SCRAPPER_VERSION','1.0.0');

    define('MANGA_SCRAPPER_TAB_STR','    ');

    function autoload_classes($class_name) {

        $dirs = array(
            MANGA_ROOT_DIR.'includes/classes/',
            MANGA_ROOT_DIR.'includes/classes/utils/',
            MANGA_ROOT_DIR.'includes/classes/chapters/',
            MANGA_ROOT_DIR.'includes/classes/manga/',
            MANGA_ROOT_DIR.'includes/classes/images/',
            MANGA_ROOT_DIR.'includes/classes/arguments/',
        );

        foreach($dirs as $d) {

            $path = $d."class.{$class_name}.php";

            if(file_exists($path)) {
                require_once($path);
                break;
            }

        }

    }

    spl_autoload_register('autoload_classes');

    /* Max console window width  */

    define('MANGA_SCRAPPER_MAX_CONSOLE_WINDOW_WIDTH',80);

    /* Console line seperator settings */

    define('MANGA_SCRAPPER_SEPERATOR_LINE_CHAR','~');
    define('MANGA_SCRAPPER_SEPERATOR_LINE_COLOR',ConsoleColors::COLOR_CYAN);
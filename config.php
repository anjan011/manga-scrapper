<?php

    require_once('functions/functions.php');

    define('MANGA_ROOT_DIR',dirname(__FILE__).'/');


    $mangaInfo = array(
        'site' => 'mangapanda',
        'chapters_url' => 'http://www.mangapanda.com/nisekoi',
        'id' => 3340,
        'slug' => 'nisekoi',
        'output_dir' => './manga/mangapanda/nisekoi'
    );

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
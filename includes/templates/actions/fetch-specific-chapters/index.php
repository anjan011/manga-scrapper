<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/5/15
     * Time: 8:33 AM
     */

    $specificChapterIds = $objArgumentsList->getChapterIds();

    Console::seperatorLine();

    consoleLinePurple("Fetching specific chapter(s): ".join(',',$specificChapterIds));

    Console::seperatorLine();

    consoleLineBlue('Checking for valid chapter ids ...');

    $newChapters = array();

    foreach($specificChapterIds as $chapterId) {

        if ( !isset($chapterrsList[ $chapterId ]) ) {
            consoleLineError( "Invalid chapter id: ".$chapterId );
            exit();
        }
        else {
            $newChapters[ $chapterId ] = $chapterrsList[ $chapterId ];
        }

    }


    if ( !empty($newChapters) ) {

        $chaptersCountToFetch = $objArgumentsList->getChaptersCount();

        if ( $chaptersCountToFetch > 0 ) {

            if ( $chaptersCountToFetch > 1 ) {
                consoleLinePurple( "Fetching only first {$chaptersCountToFetch} chapters!" );
            }
            else {
                consoleLinePurple( "Fetching only first chapter!" );
            }


        }

        // Fetch chapters ...

        require_once(MANGA_ROOT_DIR.'includes/templates/actions/__common/chapters/fetch.php');

    }
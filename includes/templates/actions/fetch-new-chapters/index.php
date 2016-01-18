<?php

    # ========================================================
    # Check for new chapters
    # ========================================================



    $completedChaptersList = $mangaStatus->getCompletedChaptersList();

    consoleLineInfo( "Completed chapters count: ".count( $completedChaptersList ), 1 );

    $newChapters = array_diff_key( $chapterrsList, $completedChaptersList );

    if ( !empty($newChapters) ) {
        consoleLinePurple( "New chapters to fetch: ".count( $newChapters ) );
    } else {
        consoleLineError( "No new chapters to fetch!" );
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


    # ========================================================
    # Update status data
    # ========================================================

    $mangaStatus->updateChaptersTotalCount( count( $chapterrsList ) );
    $mangaStatus->updateAllChaptersList( $chapterrsList );
<?php

    # ========================================================
    # Check for new chapters
    # ========================================================

    consoleLineInfo( "Found chapters: ".count( $chapterrsList ), 1 );

    $completedChaptersList = $mangaStatus->getCompletedChaptersList();

    consoleLineInfo( "Completed chapters count: ".count( $completedChaptersList ), 1 );

    $newChapters = array_diff( $chapterrsList, $completedChaptersList );

    if ( !empty($newChapters) ) {
        consoleLinePurple( "New chapters to fetch: ".count( $newChapters ) );

        $chaptersCountToFetch = $objArgumentsList->getChaptersCount();

        if ( $chaptersCountToFetch > 0 ) {

            if ( $chaptersCountToFetch > 1 ) {
                consoleLinePurple( "Fetching only first {$chaptersCountToFetch} chapters!" );
            }
            else {
                consoleLinePurple( "Fetching only first chapter!" );
            }


        }

        # ========================================================
        # Fetch new chapters
        # ========================================================

        /**
         * @var ChapterInfo $chapter
         */

        $fetchedCount = 0;

        foreach ( $newChapters as $chapter ) {

            consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' );

            consoleLineInfo( 'Fetching chapter: '.$chapter->getTitle() );

            if ( !($chapter instanceof ChapterInfo) ) {
                consoleLineError( 'Chapter info is not valid!' );
                exit();
            }

            $objChapterImages = NULL;

            $classChapterImages = $classPrefix.'ChapterImages';

            if ( class_exists( $classChapterImages ) ) {

                /**
                 * @var ChapterImages $objChapterImages
                 */

                $objChapterImages = new $classChapterImages( [
                    'mangaInfo'   => $mangaInfo,
                    'chapterInfo' => $chapter,
                ] );

                $imagesUrl = $objChapterImages->getImagePageUrls();

                consoleLinePurple( "Found images: ".count( $imagesUrl ) );

                $classImageScrapper = $classPrefix.'ImageScrapper';

                if ( class_exists( $classImageScrapper ) ) {

                    /**
                     * @var ImageScrapper $objImageScrapper
                     */

                    $objImageScrapper = new $classImageScrapper( [
                        'mangaInfo'   => $mangaInfo,
                        'chapterInfo' => $chapter,
                        'images'      => $imagesUrl,
                    ] );

                    $objImageScrapper->fetchImages();

                }
                else {
                    consoleLineError( "Class not found: ".$classImageScrapper, 2 );

                    consoleLineInfo( '' );

                    exit();
                }


            }
            else {
                consoleLineError( "Class not found: ".$classChapterImages, 2 );

                consoleLineInfo( '' );

                exit();
            }

            $fetchedCount += 1;

            // Did we reach chapters count limit? break then!

            if ( $chaptersCountToFetch > 0 && ($fetchedCount >= $chaptersCountToFetch) ) {
                break;
            }
        }

    }
    else {
        consoleLineError( "No new chapters to fetch!" );
    }


    # ========================================================
    # Update status data
    # ========================================================

    $mangaStatus->updateChaptersTotalCount( count( $chapterrsList ) );
    $mangaStatus->updateAllChaptersList( $chapterrsList );
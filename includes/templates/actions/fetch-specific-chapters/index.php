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

    consoleLineBlue('Checking chapter ids ...');

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
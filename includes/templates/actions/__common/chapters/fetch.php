<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/14/15
     * Time: 11:08 PM
     */

    # ========================================================
    # Fetch chapters
    # ========================================================

    /**
     * @var ChapterInfo $chapter
     */

    $fetchedCount = 0;

    foreach ( $newChapters as &$chapter ) {

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

            $imageUrlList = $objChapterImages->getImagePageUrls();

            consoleLinePurple( "Found images: ".count( $imageUrlList ) );

            $classImageScrapper = $classPrefix.'ImageScrapper';

            if ( class_exists( $classImageScrapper ) ) {

                /**
                 * @var ImageScrapper $objImageScrapper
                 */

                $objImageScrapper = new $classImageScrapper( [
                    'mangaInfo'   => $mangaInfo,
                    'chapterInfo' => $chapter,
                    'images'      => $imageUrlList,
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

        /**
         * @var ArgumentsList $objArgumentsList
         */

        global $objArgumentsList;

        $delay = $objArgumentsList->getChapterDelay();

        if($delay) {
            consoleLineInfo("---------------------------------------");
            consoleLineInfo("Sleeping for ".$delay.' seconds');
            consoleLineInfo("---------------------------------------");

            sleep($delay);
        }
    }
<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 2:19 PM
     */

    ini_set( 'display_errors', TRUE );
    error_reporting( E_ALL );

    $startTime = time();


    require_once('config.php');

    consoleLineInfo('');



    # ========================================================
    # Parse and prepare CLI arguments
    # ========================================================

    $objArgumentsList = ArgumentsList::getInstance(ArgumentParser::prepareCliArguments());

    # ========================================================
    # Prepare managa info instance ...
    # ========================================================

    $mangaInfo = MangaInfo::getInstance(array(
        'source' => $objArgumentsList->getSource(),
        'name' => $objArgumentsList->getMangaName(),
        'slug' => $objArgumentsList->getMangaSlug(),
        'url' => MangaSource::getInstance()->generateMangaChaptersUrl(
            $objArgumentsList->getSource(),
            array(
                'slug' => $objArgumentsList->getMangaSlug()
            )
        ),
        'output_dir' => $objArgumentsList->getOutputDir()
    ));


    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    consoleLineInfo('Strating at: '.date('M d, Y h:i a',$startTime));
    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    consoleLineInfo('Fetching chapters for: '.$mangaInfo->getName());
    consoleLineInfo('Manga Url: '.$mangaInfo->getUrl());
    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');

    # ========================================================
    # Do we have chapter titles list already?
    # ========================================================

    $objChapterTitles = ChapterTitles::getInstance(array(
        'mangaInfo' => $mangaInfo
    ));

    # ========================================================
    # Prepare manga status object. we be gonna need it!
    # ========================================================

    $mangaStatus = MangaStatus::getInstance(array(
        'mangaInfo' => $mangaInfo
    ));

    # ========================================================
    # get chapters list!
    # ========================================================

    /**
     * @var ChaptersList $objChaptersList
     */

    $objChaptersList = null;

    $classPrefix = MangaSource::getInstance()->getSourceClassPrefix($mangaInfo->getSource());

    $classChaptersList = "{$classPrefix}ChaptersList";

    if(class_exists($classChaptersList)) {
        $objChaptersList = $classChaptersList::getInstance(array(
            'mangaInfo' => $mangaInfo
        ));
    }


    if($objChaptersList) {
        $chapterrsList = $objChaptersList->getChapters();
    } else {
        consoleLineError('Unsupported manga host!');

        exit();
    }

    switch($objArgumentsList->getAction()) {
        case ArgumentsList::ACTION_FETCH_NEW_CHAPTERS:

            # ========================================================
            # Check for new chapters
            # ========================================================

            consoleLineInfo("Found chapters: ".count($chapterrsList),1);

            $completedChaptersList = $mangaStatus->getCompletedChaptersList();

            consoleLineInfo("Completed chapters count: ".count($completedChaptersList),1);

            $newChapters = array_diff($chapterrsList,$completedChaptersList);

            if(!empty($newChapters)) {
                consoleLinePurple("New chapters to fetch: ".count($newChapters));
            } else {
                consoleLineError("No new chapters to fetch!");
            }

            # ========================================================
            # Fetch new chapters
            # ========================================================

            /**
             * @var ChapterInfo $chapter
             */

            foreach($newChapters as $chapter) {

                consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');

                consoleLineInfo('Fetching chapter: '.$chapter->getTitle());

                if(!($chapter instanceof ChapterInfo)) {
                    consoleLineError('Chapter info is not valid!');
                    exit();
                }

                $objChapterImages = null;

                $classChapterImages = $classPrefix.'ChapterImages';

                if(class_exists($classChapterImages)) {

                    /**
                     * @var ChapterImages $objChapterImages
                     */

                    $objChapterImages = new $classChapterImages([
                        'mangaInfo'=> $mangaInfo,
                        'chapterInfo' => $chapter
                    ]);

                    $imagesUrl = $objChapterImages->getImagePageUrls();

                    consoleLinePurple("Found images: ".count($imagesUrl));

                    $classImageScrapper = $classPrefix.'ImageScrapper';

                    if(class_exists($classImageScrapper)) {

                        /**
                         * @var ImageScrapper $objImageScrapper
                         */

                        $objImageScrapper = new $classImageScrapper([
                            'mangaInfo' => $mangaInfo,
                            'chapterInfo' => $chapter,
                            'images' => $imagesUrl
                        ]);

                        $objImageScrapper->fetchImages();

                    } else {
                        consoleLineError("Class not found: ".$classImageScrapper,2);

                        consoleLineInfo('');

                        exit();
                    }



                } else {
                    consoleLineError("Class not found: ".$classChapterImages,2);

                    consoleLineInfo('');

                    exit();
                }
            }

            # ========================================================
            # Update status data
            # ========================================================

            $mangaStatus->updateChaptersTotalCount(count($chapterrsList));
            $mangaStatus->updateAllChaptersList($chapterrsList);

            break;
        case ArgumentsList::ACTION_EXPORT_CHAPTER_TITLES:
            break;
        default:
            $objArgumentsList->displayInvalidActionMessage(true);
            break;
    }






    $endTime = time();

    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    consoleLineInfo('Ended at: '.date('M d, Y h:i a',$endTime));
    consoleLineInfo('Total time taken: '.($endTime - $startTime).' seconds!');
    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~',2);
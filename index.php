<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 2:19 PM
     */

    ini_set( 'display_errors', TRUE );
    error_reporting( E_ALL );



    require_once('config.php');

    $mangaInfo = MangaInfo::getInstance(array(
        'source' => 'mangapanda',
        'name' => 'Nisekoi',
        'slug' => 'nisekoi',
        'url' => 'http://www.mangapanda.com/nisekoi',
        'output_dir' => './manga/mangapanda/nisekoi/'
    ));


    $startTime = time();

    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    consoleLineInfo('Strating at: '.date('M d, Y h:i a',$startTime));
    consoleLineInfo('Fetching chapters for: '.$mangaInfo->getName());
    consoleLineInfo('Manga Url: '.$mangaInfo->getUrl());
    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');

    $mangaStatus = MangaStatus::getInstance(array(
        'mangaInfo' => $mangaInfo
    ));

    $objChaptersList = null;

    switch($mangaInfo->getSource()) {

        case 'mangapanda':
            $objChaptersList = MangapandaChapterList::getInstance(array(
                'mangaInfo' => $mangaInfo
            ));
            break;

    }

    if($objChaptersList) {
        $chapterrsList = $objChaptersList->getChapters();
    } else {
        consoleLineError('Unsupported manga host!');

        exit();
    }

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

        switch($mangaInfo->getSource()) {

            case 'mangapanda':

                $objChapterImages = new MangapandaChapterImages([
                    'mangaInfo'=> $mangaInfo,
                    'chapterInfo' => $chapter
                ]);

                $imagesUrl = $objChapterImages->getImagePageUrls();

                consoleLinePurple("Found images: ".count($imagesUrl));

                $objImageScrapper = new MangaPandaImageScrapper([
                    'mangaInfo' => $mangaInfo,
                    'chapterInfo' => $chapter,
                    'images' => $imagesUrl
                ]);

                $objImageScrapper->fetchImages();

                break;

        }
    }


    # ========================================================
    # Update status data
    # ========================================================

    $mangaStatus->updateChaptersTotalCount(count($chapterrsList));
    $mangaStatus->updateAllChaptersList($chapterrsList);

    $endTime = time();

    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    consoleLineInfo('Ended at: '.date('M d, Y h:i a',$endTime));
    consoleLineInfo('Total time taken: '.($endTime - $startTime).' seconds!');
    consoleLineInfo('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~',2);
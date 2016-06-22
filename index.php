<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 2:19 PM
     */



    $startTime = time ();


    require_once ( 'config.php' );

    ini_set ( 'display_errors', TRUE );
    error_reporting ( E_ALL );

    Console::emptyLines ( 1 );


    # ========================================================
    # Parse and prepare CLI arguments
    # ========================================================

    $objArgumentsList = ArgumentsList::getInstance ( ArgumentParser::prepareCliArguments () );

    # ========================================================
    # Prepare managa info instance ...
    # ========================================================

    $mangaInfo = MangaInfo::getInstance ( array (
        'source'     => $objArgumentsList->getSource (),
        'name'       => $objArgumentsList->getMangaName (),
        'slug'       => $objArgumentsList->getMangaSlug (),
        'url'        => MangaSourceList::getInstance ()->generateMangaChaptersUrl (
            $objArgumentsList->getSource (),
            array (
                'slug' => $objArgumentsList->getMangaSlug (),
            )
        ),
        'output_dir' => $objArgumentsList->getOutputDir (),
    ) );

    Console::seperatorLine ();

    consoleLineInfo ( 'Strating at: ' . date ( 'M d, Y h:i a', $startTime ) );
    Console::seperatorLine ();
    consoleLineInfo ( 'Fetching chapters for: ' . $mangaInfo->getName () );
    consoleLineInfo ( 'Manga Url: ' . $mangaInfo->getUrl () );
    Console::seperatorLine ();

    # ========================================================
    # Do we have chapter titles list already?
    # ========================================================

    $objChapterTitles = ChapterTitles::getInstance ( array (
        'mangaInfo' => $mangaInfo,
    ) );

    # ========================================================
    # Prepare manga status object. we be gonna need it!
    # ========================================================

    $mangaStatus = MangaStatus::getInstance ( array (
        'mangaInfo' => $mangaInfo,
    ) );

    # ========================================================
    # get chapters list!
    # ========================================================

    $actionRequiringChapterFetch = array (
        ArgumentsList::ACTION_NEW_CHAPTERS,
        ArgumentsList::ACTION_SPECIFIC_CHAPTERS,
    );

    if ( in_array ( $objArgumentsList->getAction (), $actionRequiringChapterFetch ) ) {


        consoleLinePurple ( "Updating chapters list from {$mangaInfo->getSource()} ..." );

        /**
         * @var ChaptersList $objChaptersList
         */

        $objChaptersList = NULL;

        $classPrefix = MangaSourceList::getInstance ()->getSourceClassPrefix ( $mangaInfo->getSource () );

        $classChaptersList = "{$classPrefix}ChaptersList";



        if ( class_exists ( $classChaptersList ) ) {
            $objChaptersList = $classChaptersList::getInstance ( array (
                'mangaInfo' => $mangaInfo,
            ) );

        }


        if ( $objChaptersList ) {

            $chapterrsList = $objChaptersList->getChapters ();

            if(!is_array($chapterrsList) || empty($chapterrsList)) {
                consoleLineError('Unable to fetch chapters list!');
                exit();
            }
        }
        else {
            consoleLineError ( 'Unsupported manga host!' );

            exit();
        }



        consoleLineInfo ( "Found chapters: " . count ( $chapterrsList ), 1 );
    }

    switch ( $objArgumentsList->getAction () ) {

        case ArgumentsList::ACTION_NEW_CHAPTERS:

            require_once ( MANGA_ROOT_DIR . 'includes/templates/actions/fetch-new-chapters/index.php' );

            break;
        case ArgumentsList::ACTION_SPECIFIC_CHAPTERS:

            require_once ( MANGA_ROOT_DIR . 'includes/templates/actions/fetch-specific-chapters/index.php' );

            break;
        case ArgumentsList::ACTION_EXPORT_CHAPTER_TITLES:

            $objChapterTitles->dumpChapterTitles ();

            break;
        case ArgumentsList::ACTION_SHOW_CHAPTERS:

            $objChapterTitles->showChapters ();

            break;
        case ArgumentsList::ACTION_RECREATE_CBR:

            require_once ( MANGA_ROOT_DIR . 'includes/templates/actions/recreate-cbr/index.php' );

            break;
        default:
            $objArgumentsList->displayInvalidActionMessage ( TRUE );
            break;
    }


    Console::seperatorLine ();

    $endTime = time ();
    consoleLineInfo ( 'Ended at: ' . date ( 'M d, Y h:i a', $endTime ) );
    consoleLineInfo ( 'Total time taken: ' . Formatter::formattedTimeDifference ( $endTime - $startTime ) . ' !' );

    Console::seperatorLine ();
    Console::emptyLines ( 1 );
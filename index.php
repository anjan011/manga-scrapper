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

    consoleLineInfo( '' );


    # ========================================================
    # Parse and prepare CLI arguments
    # ========================================================

    $objArgumentsList = ArgumentsList::getInstance( ArgumentParser::prepareCliArguments() );

    # ========================================================
    # Prepare managa info instance ...
    # ========================================================

    $mangaInfo = MangaInfo::getInstance( array(
        'source'     => $objArgumentsList->getSource(),
        'name'       => $objArgumentsList->getMangaName(),
        'slug'       => $objArgumentsList->getMangaSlug(),
        'url'        => MangaSource::getInstance()->generateMangaChaptersUrl(
            $objArgumentsList->getSource(),
            array(
                'slug' => $objArgumentsList->getMangaSlug(),
            )
        ),
        'output_dir' => $objArgumentsList->getOutputDir(),
    ) );


    consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' );
    consoleLineInfo( 'Strating at: '.date( 'M d, Y h:i a', $startTime ) );
    consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' );
    consoleLineInfo( 'Fetching chapters for: '.$mangaInfo->getName() );
    consoleLineInfo( 'Manga Url: '.$mangaInfo->getUrl() );
    consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' );

    # ========================================================
    # Do we have chapter titles list already?
    # ========================================================

    $objChapterTitles = ChapterTitles::getInstance( array(
        'mangaInfo' => $mangaInfo,
    ) );

    # ========================================================
    # Prepare manga status object. we be gonna need it!
    # ========================================================

    $mangaStatus = MangaStatus::getInstance( array(
        'mangaInfo' => $mangaInfo,
    ) );

    # ========================================================
    # get chapters list!
    # ========================================================

    /**
     * @var ChaptersList $objChaptersList
     */

    $objChaptersList = NULL;

    $classPrefix = MangaSource::getInstance()->getSourceClassPrefix( $mangaInfo->getSource() );

    $classChaptersList = "{$classPrefix}ChaptersList";

    if ( class_exists( $classChaptersList ) ) {
        $objChaptersList = $classChaptersList::getInstance( array(
            'mangaInfo' => $mangaInfo,
        ) );
    }


    if ( $objChaptersList ) {
        $chapterrsList = $objChaptersList->getChapters();
    }
    else {
        consoleLineError( 'Unsupported manga host!' );

        exit();
    }

    switch ( $objArgumentsList->getAction() ) {

        case ArgumentsList::ACTION_FETCH_NEW_CHAPTERS:

            require_once(MANGA_ROOT_DIR.'includes/templates/actions/fetch-new-chapters/index.php');

            break;
        case ArgumentsList::ACTION_EXPORT_CHAPTER_TITLES:
            break;
        default:
            $objArgumentsList->displayInvalidActionMessage( TRUE );
            break;
    }


    $endTime = time();

    consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' );
    consoleLineInfo( 'Ended at: '.date( 'M d, Y h:i a', $endTime ) );
    consoleLineInfo( 'Total time taken: '.($endTime - $startTime).' seconds!' );
    consoleLineInfo( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~', 2 );
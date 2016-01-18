<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/6/15
     * Time: 11:24 AM
     */

    /*

    'output-dir',
    'chapter-ids',
    'help'
     * */

    $paramPadLength = 20;

    consoleLinePurple('Manga Scrapper v'.MANGA_SCRAPPER_VERSION);

    Console::emptyLines(1);

    $tabs = 0;

    Console::writeMultiline('This script is intended to scrap manga episodes from various sources. It fetches the images for chapters and creates a .cbr (comic book reader) file automatically. It can automatically query supported sources for a given manga series chapters and fetch new chapters if they are available. It is also aware of incomplete chapters. That is a chapter with not all images downloaded. In such cases it will only download images that are not downloaded already. It will skip image download if all images are fetched already.',MANGA_SCRAPPER_TAB_STR);

    Console::emptyLines(1);

    consoleLinePurple('Available params -',2);

    $emptyPaddedStr = __pad_space_right('',$paramPadLength);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 5:00 PM
    # ====================================================================
    # --action
    # ====================================================================

    Console::text(__pad_space_right("--action",$paramPadLength),1);

    $blockText = 'Action to perform. See below for a list of supported actions.';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 4:55 PM
    # ====================================================================
    # --chapters-count
    # ====================================================================

    Console::text(__pad_space_right("--chapters-count",$paramPadLength),1);

    $blockText = 'Number of chapters to fetch. If a positive number is specified then only that number of chapters from the first will be fetched for the current run.';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 4:55 PM
    # ====================================================================
    # --chapter-ids
    # ====================================================================

    Console::text(__pad_space_right("--chapter-id",$paramPadLength),1);

    $blockText = 'Comma seperated list of chapter ids. Both individual chapter id and chapter ranges. Chapter ranges are replaced with integer chapter ids within and including the start and end. The final parsed chapter id list is sorted and free of duplicates. Example: 1,2,3,10-16,18 becomes 1,2,3,10,11,12,13,14,15,16,18';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 4:55 PM
    # ====================================================================
    # --help
    # ====================================================================

    Console::text(__pad_space_right("--help",$paramPadLength),1);

    $blockText = 'This help screen';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 5:00 PM
    # ====================================================================
    # --name
    # ====================================================================

    Console::text(__pad_space_right("--name",$paramPadLength),1);

    $blockText = 'Nice name for the manga. Strictly decorative, as if ommitted defaults to manga slug value.';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 5:00 PM
    # ====================================================================
    # --output-dir
    # ====================================================================

    Console::text(__pad_space_right("--output-dir",$paramPadLength),1);

    $blockText = 'The directory where the downloaded files and data files will be saved to. If omitted defaults to ./manga/{source}/{slug}/ If the directory doesnot exists, the script tries to create it. Failing so it will exit. The script will also exit if the directory is not writable.';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 12:15 PM
    # ====================================================================
    # --slug
    # ====================================================================

    Console::text(__pad_space_right("--slug",$paramPadLength),1);

    $blockText = 'The manga slug or id by which the manga is identified on the source site. This is usually part of the mnaga chapters url page.'."Example: ".Console::text("nisekoi",0,ConsoleColors::COLOR_CYAN,true).' from '.Console::text("http://www.mangapanda.com/nisekoi",0,ConsoleColors::COLOR_CYAN,true);

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);

    # ====================================================================
    # By: anjan @ Nov 06, 2015 12:15 PM
    # ====================================================================
    # --source
    # ====================================================================

    Console::text(__pad_space_right("--source",$paramPadLength),1);

    $blockText = 'Manga sources/sites to fetch data from. Supported sources: ';

    $temp = [];

    foreach(MangaSourceList::getInstance()->getAllowedSourceList() as $src_key => $src_data) {

        $temp[] = Console::text($src_key,0,ConsoleColors::COLOR_CYAN,true);

    }

    $blockText .= join(', ',$temp);

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);








    # ====================================================================
    # By: anjan @ Nov 06, 2015 12:15 PM
    # ====================================================================
    # --url
    # ====================================================================

    Console::text(__pad_space_right("--url",$paramPadLength),1);

    $blockText = 'Accepts an url to manga chapters list or a specific chapter. If a valid manga chapter list url is specified, then the source, and slug param is ignored. Also, if the url is for a chapter, in addition to source and slug, action and chapter-ids params are ignored as well.';

    Console::writeMultiline($blockText,MANGA_SCRAPPER_TAB_STR.$emptyPaddedStr,'',true);


    /*********************************************************************
     * By: Anjan @ Nov 06, 2015 5:13 PM
     *********************************************************************
     * Available actions
     *********************************************************************/

    Console::emptyLines(1);

    consoleLinePurple('List of supported actions -',2);

    $actionKeyLengths = array();

    foreach(ArgumentsList::getActionList() as $key => $data) {

        $actionKeyLengths[] = strlen($key);

    }

    $maxkeyLen = max($actionKeyLengths) + 2 * strlen(MANGA_SCRAPPER_TAB_STR);

    foreach(ArgumentsList::getActionList() as $key => $data) {

        Console::text(__pad_space_right(MANGA_SCRAPPER_TAB_STR.$key.MANGA_SCRAPPER_TAB_STR,$maxkeyLen),0,ConsoleColors::COLOR_CYAN);

        Console::writeMultiline($data['desc'].($data['default'] ? Console::text(' [default]',0,ConsoleColors::COLOR_RED,true):''),__pad_space_right('',$maxkeyLen),'',true);

    }

    Console::emptyLines(1);
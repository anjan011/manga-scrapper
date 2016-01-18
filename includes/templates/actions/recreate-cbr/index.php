<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/17/15
     * Time: 6:37 PM
     */

    consoleLinePurple('Recreating .cbr files ...');

    consoleLineBlue("CBR Dir: ".$mangaInfo->getCbrDirPath());







    if($objArgumentsList->shouldKeepCbrBackup()) {

        $cbrBackupDir = $mangaInfo->getOutputDir().'cbr-backup/'.date('Y-m-d-H-i').'/';

        consoleLineInfo('Backing up old .cbr files to '.$cbrBackupDir);

        if(!is_dir($cbrBackupDir)) {

            if(!mkdir($cbrBackupDir,0777,true)) {
                consoleLineError("Unable to create cbr backup dir [{$cbrBackupDir}] ");
                exit();
            }

        }

        exec("cp ".$mangaInfo->getCbrDirPath()."/*.cbr {$cbrBackupDir}/");

    } else {

        consoleLineInfo('Removing .existing cbr files ...');

    }

    exec("rm ".$mangaInfo->getCbrDirPath()."/*.cbr");

    consoleLineInfo('Done');

    $chapters = $mangaStatus->getAllChaptersList();

    $objChaptersTitles = ChapterTitles::getInstance();

    /**
     * @var ChapterInfo $c
     */

    foreach($chapters as &$c) {

        $title = trim($objChaptersTitles->getChapterTitle($c->getNumber()));

        if($title) {
            $c->setTitle($title);
        }

        $cbrCreator = new CbrCreator(array(
            'mangaInfo' => $mangaInfo,
            'chapterInfo' => $c
        ));

        $cbrCreator->setPrintRarOutput(false);

        $cbrCreator->createCbr();

    }

    $mangaStatus->updateAllChaptersList($chapters);
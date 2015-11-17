<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/17/15
     * Time: 6:37 PM
     */

    consoleLinePurple('Recreating .cbr files ...');

    consoleLineBlue("CBR Dir: ".$mangaInfo->getCbrDirPath());



    $cbrBackupDir = $mangaInfo->getOutputDir().'cbr-backup/'.date('Y-m-d-H-i').'/';

    consoleLineInfo('Backing up old .cbr files to '.$cbrBackupDir);

    if(!is_dir($cbrBackupDir)) {

        if(!mkdir($cbrBackupDir,0777,true)) {
            consoleLineError("Unable to create cbr backup dir [{$cbrBackupDir}] ");
            exit();
        }

    }

    consoleLineInfo('Backing up existing cbr files ...');
    exec("cp ".$mangaInfo->getCbrDirPath()."/*.cbr {$cbrBackupDir}/");
    exec("rm ".$mangaInfo->getCbrDirPath()."/*.cbr");
    consoleLineInfo('Done');

    $chapters = $mangaStatus->getAllChaptersList();

    foreach($chapters as $c) {

        $cbrCreator = new CbrCreator(array(
            'mangaInfo' => $mangaInfo,
            'chapterInfo' => $c
        ));

        consoleLineInfo($cbrCreator->createCbr());

    }
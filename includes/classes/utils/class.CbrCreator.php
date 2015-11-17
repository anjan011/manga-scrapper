<?php

    /**
     * Creates a cbr file from chapter images
     * User: anjan
     * Date: 11/4/15
     * Time: 9:29 PM
     */

    class CbrCreator {

        /**
         * @var MangaInfo $_mangaInfo
         */

        private $_mangaInfo = null;

        /**
         * @var ChapterInfo $_chapterInfo
         */

        private $_chapterInfo = null;

        function __construct($params = array()) {

            if(isset($params['mangaInfo']) && ($params['mangaInfo'] instanceof MangaInfo)) {
                $this->_mangaInfo = $params['mangaInfo'];
            } else {
                consoleLineError("A MangaInfo object is required for CbrCreator");
                exit();
            }

            if(isset($params['chapterInfo']) && ($params['chapterInfo'] instanceof ChapterInfo)) {
                $this->_chapterInfo = $params['chapterInfo'];
            } else {
                consoleLineError("A ChapterInfo object is required for CbrCreator");
                exit();
            }

        }

        public function createCbr() {

            $chapterImageDir = $this->_mangaInfo->getOutputDir().'images/'.$this->_chapterInfo->getNumber().'/';

            if(!is_dir($chapterImageDir)) {
                consoleLineError("Chapter image dir {$chapterImageDir} not found!");
                exit();
            }

            $cbrDirPath = $this->_mangaInfo->getCbrDirPath();

            if(!is_dir($cbrDirPath)) {
                consoleLineError("Cbr dir {$cbrDirPath} not found!");
                exit();
            }

            $cNum = $this->_chapterInfo->getNumber();
            $cTitle = $this->_chapterInfo->getTitle();
            $mSlug = $this->_mangaInfo->getSlug();

            $cbrFileName = '['.$mSlug.'-'.str_pad($cNum,6,'0',STR_PAD_LEFT).'] - '.Sanitization::stripNonwordCharachters($cTitle,'-','lower').'.cbr';

            $shellCommand = "rar a \"{$cbrDirPath}{$cbrFileName}\" {$chapterImageDir}*.jpg";

            $this->_chapterInfo->setCbrFileName($cbrFileName);

            consoleLineInfo(shell_exec($shellCommand));

            consoleLinePurple("Created CBR file: ".$cbrFileName);

        }

        /**
         * @return MangaInfo
         */
        public function getMangaInfo () {

            return $this->_mangaInfo;
        }

        /**
         * @return ChapterInfo
         */
        public function getChapterInfo () {

            return $this->_chapterInfo;
        }

    }
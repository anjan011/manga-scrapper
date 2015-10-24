<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 12:16 PM
     */
    class ChapterTitles {

        private $_titlesCsvFilePath = '';

        /**
         * List of chapter titles
         *
         * @var array
         */

        private $_chapters = array();

        /**
         * @var ChapterTitles $instance
         */
        private static $instance = NULL;

        /**
         * @param array $data
         *
         * @return ChapterTitles
         */

        public static function getInstance( $data = array() ) {

            if ( self::$instance === NULL ) {
                self::$instance = new self( $data );
            }

            return self::$instance;
        }




        /**
         * @var MangaInfo $_mangaInfo
         */

        private $_mangaInfo = null;

        /**
         * Parse data
         *
         * @param array $data
         */

        private function parseData($data = array()) {

            if(isset($data['mangaInfo']) && ($data['mangaInfo'] instanceof MangaInfo)) {

                $this->_mangaInfo = $data['mangaInfo'];

            } else {
                consoleLineError("MangaInfo object not provided!");
                exit();
            }

            if(isset($data['titlesCsvFilePath']) && trim($data['titlesCsvFilePath']) != '') {

                $titlesCsvFilePath = trim($data['titlesCsvFilePath']);

                if(is_readable($titlesCsvFilePath)) {
                   $this->_titlesCsvFilePath = $titlesCsvFilePath;
                } else {
                    consoleLineError('Chapters titles csv file '.$titlesCsvFilePath.' not found!');
                    exit();
                }

            } else {

                $mangaDir = $this->_mangaInfo->getOutputDir();

                $source = $this->_mangaInfo->getSource();

                $this->_titlesCsvFilePath = $mangaDir.'/'.'chapter-titles.'.$source.'.csv';

                if(!is_readable($this->_titlesCsvFilePath)) {
                    $this->_titlesCsvFilePath = $mangaDir.'/'.'chapter-titles.csv';
                }

            }
        }

        /**
         * @param array $data
         */

        private function __construct($data = array()) {

            $this->parseData($data);

            $this->parseChapterTitlesFromFile();

        }

        /**
         * parse chapter titles from titles list
         *
         * @return bool
         */

        public function parseChapterTitlesFromFile() {

            $titlesFile = $this->_titlesCsvFilePath;


            if(!file_exists($titlesFile)) {
                consoleLineError("Chapters title file ".basename($titlesFile).' not found!');
                return false;
            }

            $f = fopen($titlesFile,'r');

            if(!$f) {
                consoleLineError("Unable to open file ".$titlesFile);
                exit();
            }

            while($row = fgetcsv($f)) {



                $chapterNumber = isset($row[0]) ? trim($row[0]) : '';
                $chapterTitle = isset($row[1]) ? trim($row[1]) : '';

                if($chapterNumber != '' && $chapterTitle != '') {

                    $this->_chapters[$chapterNumber] = $chapterTitle;

                }

            }

            fclose($f);

        }

        /**
         * get chapter title
         *
         * @param int $number
         *
         * @return string
         */

        public function getChapterTitle($number = 1) {

            if(isset($this->_chapters[$number])) {

                return $this->_chapters[$number];

            }

            return '';

        }

    }
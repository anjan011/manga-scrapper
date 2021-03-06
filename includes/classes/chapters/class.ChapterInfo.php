<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 1:54 PM
     */

    class ChapterInfo {

        /**
         * @var MangaInfo $_mangaInfo
         */

        private $_mangaInfo = null;

        private $_number = null;

        private $_title = null;

        private $_url = null;

        private $_title_safe = null;

        private $_cbr_file_name = null;

        function __construct($data = array()) {

            if(!isset($data['mangaInfo']) || !($data['mangaInfo'] instanceof MangaInfo)) {
                consoleLineError('ChapterInfo object requires a MangaInfo object!');
            }

            $this->_mangaInfo = $data['mangaInfo'];

            $this->_number = Input::array_value($data,'number','','trim');

            if($this->_number == '') {
                consoleLineError('Chpater number is required!');
                exit();
            }

            $this->_url = Input::array_value($data,'url','','trim');

            if($this->_url == '') {
                consoleLineError('Chapter url si required!');
                exit();
            }


            $this->_title = Input::array_value($data,'title','','trim');

            if($this->_title == '') {
                consoleLineError('Chapter title is required!');
                exit();
            }

            $this->_title_safe = Sanitization::stripNonwordCharachters($this->_title,'-','lower');

            $this->_cbr_file_name = sprintf("[%s-%s] %s.cbr",$this->_mangaInfo->getSlug(),$this->_number,$this->_title_safe);
        }

        /**
         * @return null
         */
        public function getUrl() {

            return trim($this->_url);
        }

        /**
         * @return null
         */
        public function getTitle() {

            return trim($this->_title);
        }

        /**
         * @param bool $original
         *
         * @return null
         */
        public function getNumber($original = false) {

            if($original) {
                return  trim($this->_number);
            } else {
                return  Sanitization::stripNonwordCharachters(trim($this->_number));
            }

        }

        /**
         * @return mixed|null|string
         */

        public function getTitleSafe() {

            return $this->_title_safe;
        }

        function __toString() {
            return serialize($this);
        }

        /**
         * @return null
         */
        public function getCbrFileName() {

            return trim($this->_cbr_file_name);
        }

        /**
         * @param null $cbr_file_name
         */
        public function setCbrFileName( $cbr_file_name ) {

            $this->_cbr_file_name = trim($cbr_file_name);
        }

        /**
         * @param mixed|null $title
         */
        public function setTitle ( $title ) {

            $this->_title = trim($title);
        }

    }
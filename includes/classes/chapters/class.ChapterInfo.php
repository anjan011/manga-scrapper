<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 1:54 PM
     */

    class ChapterInfo {

        private $_number = null;

        private $_title = null;

        private $_url = null;

        private $_title_safe = null;

        function __construct($data = array()) {

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


            $this->_title = Input::array_value($data,'title','Chapter '.$this->_number,'trim');

            if($this->_title == '') {
                consoleLineError('Chapter title is required!');
                exit();
            }

            $this->_title_safe = Sanitization::stripNonwordCharachters($this->_title,'-','lower');


        }

        /**
         * @return null
         */
        public function getUrl() {

            return $this->_url;
        }

        /**
         * @return null
         */
        public function getTitle() {

            return $this->_title;
        }

        /**
         * @return null
         */
        public function getNumber() {

            return $this->_number;
        }

        /**
         * @param null $title_safe
         */
        public function setTitleSafe( $title_safe ) {

            $this->_title_safe = $title_safe;
        }

        function __toString() {
            return serialize($this);
        }

    }
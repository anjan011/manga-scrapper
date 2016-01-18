<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 12:41 PM
     */
    class ImageInfo {

        private $_number = null;

        private $_pageUrl = null;

        function __construct($data = array()) {

            $this->_number = Input::array_value($data,'number','','trim');

            $this->_pageUrl = Input::array_value($data,'pageUrl','','trim');

        }

        /**
         * @return null
         */
        public function getNumber() {

            return trim($this->_number);
        }

        /**
         * @return null
         */
        public function getPageUrl() {

            return trim($this->_pageUrl);
        }

    }
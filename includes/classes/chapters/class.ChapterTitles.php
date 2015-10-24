<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 12:16 PM
     */
    class ChapterTitles {

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
         * @param array $data
         */

        private function __construct($data = array()) {

            if(isset($data['mangaInfo']) && ($data['mangaInfo'] instanceof MangaInfo)) {

                $this->_mangaInfo = $data['mangaInfo'];

            }

        }

    }
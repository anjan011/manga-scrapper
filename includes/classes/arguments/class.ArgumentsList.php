<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 5:14 PM
     */

    class ArgumentsList {

        const ACTION_FETCH_NEW_CHAPTERS = 'fetch_new_chapters';
        const ACTION_FETCH_CHAPTER_TITLES = 'fetch_chapter_titles';
        const ACTION_EXPORT_CHAPTER_TITLES = 'export_chapter_titles';

        /**
         * An array of action list
         *
         * @var array
         */

        private $_action_list = array(
            self::ACTION_EXPORT_CHAPTER_TITLES,
            self::ACTION_FETCH_CHAPTER_TITLES,
            self::ACTION_FETCH_NEW_CHAPTERS
        );

        private $_argumentsList = array();

        private $_action = self::ACTION_FETCH_NEW_CHAPTERS;

        /**
         * is a valid action?
         *
         * @param string $action
         *
         * @return bool
         */

        public function isValidAction($action = '') {

            if(!is_string($action)) {
                return false;
            }

            return in_array($action,$this->_action_list);
        }



        /**
         * @var ArgumentsList $instance
         */
        private static $instance = NULL;

        /**
         * @param array $data
         *
         * @return ArgumentsList
         */

        public static function getInstance( $data = array() ) {

            if ( self::$instance === NULL ) {
                self::$instance = new self( $data );
            }

            return self::$instance;
        }

        /**
         * Private constructor needed for singleton
         *
         * @param array $data
         */

        private function __construct($data = array()) {

            $this->parseData($data);

        }

        /**
         * Parses argument data
         *
         * @param array $data
         */

        private function parseData( $data = array() ) {

            $data = is_array($data) ? $data : array();

            $this->_argumentsList = $data;

            // action

            $action = Input::array_value($data,'action','','trim');

            if($action == '') {
                $action = self::ACTION_FETCH_NEW_CHAPTERS;
            }

            if(!$this->isValidAction($action)) {
                consoleLineError('Invalid action!',2);

                consoleLineInfo('Supported Actions - ');

                foreach($this->_action_list as $ac) {
                    consoleLineInfo("\t* ".$ac);
                }

                consoleLineInfo('');

                exit();
            }

        }

        /**
         * @return array
         */

        public function getArgumentsList() {

            return is_array($this->_argumentsList) ? $this->_argumentsList : array();
        }

        /**
         * get action name
         *
         * @return string
         */
        public function getAction() {

            return trim($this->_action);
        }

    }
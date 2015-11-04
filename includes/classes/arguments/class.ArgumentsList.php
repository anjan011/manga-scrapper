<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 5:14 PM
     */

    class ArgumentsList {

        const ACTION_FETCH_NEW_CHAPTERS = 'fetch_new_chapters';

        const ACTION_EXPORT_CHAPTER_TITLES = 'export_chapter_titles';

        const ACTION_UPDATE_CHAPTER_TITLES = 'update_chapter_titles';

        /**
         * An array of action list
         *
         * @var array
         */

        private $_action_list = array(
            self::ACTION_EXPORT_CHAPTER_TITLES => array(
                'desc' => 'Exports chapter titles to a CSV file'
            ),

            self::ACTION_FETCH_NEW_CHAPTERS => array(
                'desc' => 'Check for new chapters and fetch them'
            ),

            self::ACTION_UPDATE_CHAPTER_TITLES => array(
                'desc' => 'Updates chapter titles from titles CSV file'
            ),
        );

        private $_argumentsList = array();

        private $_action = self::ACTION_FETCH_NEW_CHAPTERS;

        private $_source = MangaSource::SOUCE_MANGAPANDA;

        private $_mangaSlug = '';

        private $_mangaName = '';

        private $_output_dir = '';

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

            return isset($this->_action_list[$action]);
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

        public function displayInvalidActionMessage($exit = true) {

            consoleLineError('Invalid action!',2);

            consoleLinePurple('Example: --action='.self::ACTION_FETCH_NEW_CHAPTERS,2);

            consoleLineInfo('Supported Actions - ');

            foreach($this->_action_list as $ac => $acData) {
                consoleLineInfo("\t* ".$ac.' - '.$acData['desc']);
            }

            consoleLineInfo('');

            if($exit) {
                exit();
            }


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

                $this->displayInvalidActionMessage(true);
            }

            // source

            $source = Input::array_value($data,'source',MangaSource::SOUCE_MANGAPANDA,'trim');

            if(MangaSource::getInstance()->isValidSource($source)) {
                $this->_source = $source;
            } else {
                MangaSource::getInstance()->displayInvalidMangaSourceMessage(true);
            }

            // slug

            $slug = Input::array_value($data,'slug','','trim');

            if($slug == '') {
                consoleLineError('Manga slug is required!',2);

                consoleLinePurple('Example: --slug=nisekoi',2);

                consoleLineInfo('Slug usualy means the SEO friendly name of the manga.',1);
                consoleLineInfo('But it can be different for different manga sources.',1);
                consoleLineInfo('The slug is part of the manga chapters list url.',2);

                consoleLineInfo('');

                exit();
            }

            $this->_mangaSlug = $slug;

            // name

            $name = Input::array_value($data,'name','','trim');

            if($name == '') {
                $name = $this->_mangaSlug;
            }

            $this->_mangaName = $name;

            // Output dir

            $output_dir = Input::array_value($data,'output-dir','','trim');

            if($output_dir == '') {
                $output_dir = './manga/'.$this->_source.'/'.$this->_mangaSlug.'/';
            }

            if(!is_dir($output_dir)) {

                if(!mkdir($output_dir,0777,true)) {

                    consoleLineError("Unable to create output dir: ".$output_dir,2);

                    consoleLineInfo('');

                    exit();


                }
            } else {

                $tmpFile = tempnam($output_dir,'mst-');

                if(!fopen($tmpFile,'w')) {

                    consoleLineError("Output dir is not writeable!".$output_dir,2);

                    consoleLineInfo('');

                    exit();

                } else {
                    @unlink($tmpFile);
                }

            }

            $this->_output_dir = $output_dir;

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

        /**
         * Get source name
         *
         * @return string
         */
        public function getSource() {

            return trim($this->_source);
        }

        /**
         * @return string
         */
        public function getMangaSlug() {

            return trim($this->_mangaSlug);
        }

        /**
         * @return string
         */
        public function getMangaName() {

            return trim($this->_mangaName);
        }

        /**
         * @return string
         */
        public function getOutputDir() {

            return $this->_output_dir;
        }

    }
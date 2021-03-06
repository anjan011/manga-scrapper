<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 5:14 PM
     */
    class ArgumentsList {

        const ACTION_NEW_CHAPTERS = 'new-chapters';

        const ACTION_SPECIFIC_CHAPTERS = 'specific-chapters';

        const ACTION_SHOW_CHAPTERS = 'show-chapters';

        const ACTION_EXPORT_CHAPTER_TITLES = 'export-chapter-titles';

        const ACTION_UPDATE_CHAPTER_TITLES = 'update-chapter-titles';

        const ACTION_RECREATE_CBR = 'recreate-cbr';

        private $_allowed_param_names = array(
            'slug',
            'source',
            'chapters-count',
            'action',
            'name',
            'output-dir',
            'chapter-ids',
            'help',
            'url',
            'create-cbr',
            'no-cbr-backup',
            'image-delay',
            'chapter-delay'
        );

        /**
         * An array of action list
         *
         * @var array
         */

        private static $_action_list = array(

            self::ACTION_EXPORT_CHAPTER_TITLES => array(
                'desc'    => 'Exports chapter titles to a CSV file',
                'default' => FALSE,
            ),

            self::ACTION_NEW_CHAPTERS => array(
                'desc'    => 'Check for new chapters and fetch them',
                'default' => TRUE,
            ),

            self::ACTION_SPECIFIC_CHAPTERS => array(
                'desc'    => 'Fetch specific chapters by id',
                'default' => FALSE,
            ),

            self::ACTION_SHOW_CHAPTERS => array(
                'desc'    => 'Show chapterss',
                'default' => FALSE,
            ),

            self::ACTION_UPDATE_CHAPTER_TITLES => array(
                'desc'    => 'Updates chapter titles from titles CSV file',
                'default' => FALSE,
            ),

            self::ACTION_RECREATE_CBR => array(
                'desc'    => 'Recreates .cbr files',
                'default' => FALSE,
            ),
        );

        private $_argumentsList = array();

        private $_action = self::ACTION_NEW_CHAPTERS;

        private $_source = MangaSourceList::SOUCE_MANGAPANDA;

        private $_mangaSlug = '';

        private $_mangaName = '';

        private $_output_dir = '';

        private $_chapters_count = 0;

        private $_chapter_ids = array();

        private $_show_help = FALSE;

        private $_create_cbr = TRUE;

        private $_no_cbr_backup = FALSE;

        private $_username = '';

        private $_password = '';

        private $_chapter_delay = '';

        private $_image_delay = '';

        /**
         * is a valid action?
         *
         * @param string $action
         *
         * @return bool
         */

        public function isValidAction( $action = '' ) {

            if ( !is_string( $action ) ) {
                return FALSE;
            }

            return isset(self::$_action_list[ $action ]);
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

        private function __construct( $data = array() ) {

            $this->parseData( $data );

        }

        /**
         * Displays invalid action message and optionally exits
         *
         * @param bool|TRUE $exit
         */

        public function displayInvalidActionMessage( $exit = TRUE ) {

            consoleLineError( 'Invalid action!', 2 );

            consoleLinePurple( 'Example: --action='.self::ACTION_NEW_CHAPTERS, 2 );

            consoleLineInfo( 'Supported Actions - ' );

            foreach ( self::$_action_list as $ac => $acData ) {
                consoleLineInfo( "\t* ".$ac.' - '.$acData[ 'desc' ] );
            }

            consoleLineInfo( '' );

            if ( $exit ) {
                exit();
            }


        }

        /**
         * Parses argument data
         *
         * @param array $data
         */

        private function parseData( $data = array() ) {

            // show help if requested and exit!

            if ( isset($data[ 'help' ]) ) {
                require_once(MANGA_ROOT_DIR.'includes/templates/help/index.php');
                exit();
            }

            $data = is_array( $data ) ? $data : array();

            // image delay

            $this->setImageDelay(Input::array_value( $data, 'image-delay', '', 'trim' ));

            // chapter delay

            $this->setChapterDelay(Input::array_value( $data, 'chapter-delay', '', 'trim' ));

            // url

            if ( isset($data[ 'url' ]) ) {

                $url = trim( $data[ 'url' ] );

                if ( $url == '' ) {
                    consoleLineError( "Url parameter cannot be empty!" );
                    exit();
                }

                $parsedData = UrlParser::parseUrl( $url );

                if ( !$parsedData ) {
                    consoleLineError( "Provided url is not is not valid!" );
                    exit();
                }
                else {

                    $data[ 'source' ] = $parsedData[ 'source' ];

                    $data[ 'slug' ] = $parsedData[ 'slug' ];

                    $chapter = trim( $parsedData[ 'chapter' ] );

                    if ( $chapter != '' ) {
                        $data[ 'chapter-ids' ] = $chapter;
                        $data[ 'action' ] = self::ACTION_SPECIFIC_CHAPTERS;
                    }

                }

            }

            // check for valid params

            $dataKeys = array_keys( $data );

            $diff = array_diff( $dataKeys, $this->_allowed_param_names );

            if ( count( $diff ) > 0 ) {

                consoleLineError( "Invalid params: ".join( ',', $diff ), 2 );
                exit();

            }

            $this->_argumentsList = $data;

            // action

            $action = Input::array_value( $data, 'action', '', 'trim' );


            if ( $action == '' ) {
                $action = self::ACTION_NEW_CHAPTERS;
            }

            if ( !$this->isValidAction( $action ) ) {

                $this->displayInvalidActionMessage( TRUE );
            }
            else {
                $this->_action = $action;

                if ( $this->_action == self::ACTION_SPECIFIC_CHAPTERS ) {

                    $chapterIds = Input::array_value( $data, 'chapter-ids', '', 'trim' );

                    if ( $chapterIds == '' ) {
                        consoleLineError( 'One or more chapter ids are required when action is "'.self::ACTION_SPECIFIC_CHAPTERS.'"' );
                        Console::emptyLines();
                        exit();
                    }

                }
            }

            // source

            $source = Input::array_value( $data, 'source', MangaSourceList::SOUCE_MANGAPANDA, 'trim' );


            if ( MangaSourceList::getInstance()->isValidSource( $source ) ) {
                $this->_source = $source;
            }
            else {
                MangaSourceList::getInstance()->displayInvalidMangaSourceMessage( TRUE );
            }

            // slug

            $slug = Input::array_value( $data, 'slug', '', 'trim' );

            if ( $slug == '' ) {
                consoleLineError( 'Manga slug is required!', 2 );

                consoleLinePurple( 'Example: --slug=nisekoi', 2 );

                Console::writeMultiline( 'Slug usualy means the SEO friendly name of the manga. But it can be different for different manga sources.The slug is part of the manga chapters list url.' );

                consoleLineInfo( '' );

                exit();
            }

            $this->_mangaSlug = $slug;

            // name

            $name = Input::array_value( $data, 'name', '', 'trim' );

            if ( $name == '' ) {
                $name = $this->_mangaSlug;
            }

            $this->_mangaName = $name;

            // Output dir

            $output_dir = Input::array_value( $data, 'output-dir', '', 'trim' );

            if ( $output_dir == '' ) {
                $output_dir = './manga/'.$this->_source.'/'.$this->_mangaSlug.'/';
            }

            if ( !is_dir( $output_dir ) ) {

                if ( !mkdir( $output_dir, 0777, TRUE ) ) {

                    consoleLineError( "Unable to create output dir: ".$output_dir, 2 );

                    consoleLineInfo( '' );

                    exit();


                }
            }
            else {

                $tmpFile = tempnam( $output_dir, 'mst-' );

                if ( !fopen( $tmpFile, 'w' ) ) {

                    consoleLineError( "Output dir is not writeable!".$output_dir, 2 );

                    consoleLineInfo( '' );

                    exit();

                }
                else {
                    @unlink( $tmpFile );
                }

            }

            $this->_output_dir = $output_dir;

            # chapters count

            $chaptersCount = Input::array_value_as_int( $data, 'chapters-count', 0 );

            if ( $chaptersCount < 0 ) {
                $chaptersCount = 0;
            }

            $this->_chapters_count = $chaptersCount;

            # chapter ids

            $chapterIds = Input::array_value( $data, 'chapter-ids', '', 'trim' );

            if ( $chapterIds == '' ) {

                $this->_chapter_ids = array();

            }
            else {

                // is it a file?

                if ( is_readable( $chapterIds ) ) {
                    $chapterIds = trim( file_get_contents( $chapterIds ) );
                }

                $chapterIds = explode( ',', $chapterIds );

                $chapterIds = array_map( 'trim', $chapterIds );

                // check for ranges

                $chapterRangesIds = array();

                foreach ( $chapterIds as $k => $v ) {

                    $cid = $chapterIds[ $k ];

                    if ( preg_match( '/([0-9.]+)\s*-\s*([0-9.]+)/im', $cid, $regs ) ) {

                        $chapterRangesIds[ $k ] = array(
                            'start' => $regs[ 1 ],
                            'end'   => $regs[ 2 ],
                        );

                    }

                }

                if ( count( $chapterRangesIds ) > 0 ) {

                    // unset the range format entries first, as we are gonna get real
                    // chapter ids from that range next

                    foreach ( $chapterRangesIds as $k => $rangeData ) {
                        unset($chapterIds[ $k ]);
                    }

                    // get available chapters from ranges

                    foreach ( $chapterRangesIds as $k => $rangeData ) {

                        $start = $rangeData[ 'start' ];
                        $end = $rangeData[ 'end' ];

                        for ( $i = $start; $i <= $end; $i += 1 ) {
                            $chapterIds[] = $i;
                        }

                    }

                }

                asort( $chapterIds );

                $chapterIds = array_unique( $chapterIds );

                $this->_chapter_ids = $chapterIds;




            }




            # create cbr

            $createCbr = isset($data[ 'create-cbr' ]) ? $data[ 'create-cbr' ] : TRUE;

            $result = strtolower( exec( 'type -p rar' ) );

            if ( strpos( $result, 'not found' ) ) {
                consoleLineError( 'rar doesnt seem to be installed in the system!' );

                $createCbr = FALSE;
            }

            $this->_create_cbr = $createCbr;

            if ( !$this->_create_cbr ) {
                consoleLineError( '.cbr files will not be created!' );
            }

            # no cbr backup

            if ( $this->_action == self::ACTION_RECREATE_CBR ) {

                $this->_no_cbr_backup = isset($data[ 'no-cbr-backup' ]) && $data[ 'no-cbr-backup' ];

            }

        }

        /**
         * @return array
         */

        public function getArgumentsList() {

            return is_array( $this->_argumentsList ) ? $this->_argumentsList : array();
        }

        /**
         * get action name
         *
         * @return string
         */
        public function getAction() {

            return trim( $this->_action );
        }

        /**
         * Get source name
         *
         * @return string
         */
        public function getSource() {

            return trim( $this->_source );
        }

        /**
         * @return string
         */
        public function getMangaSlug() {

            return trim( $this->_mangaSlug );
        }

        /**
         * @return string
         */
        public function getMangaName() {

            return trim( $this->_mangaName );
        }

        /**
         * @return string
         */
        public function getOutputDir() {

            return $this->_output_dir;
        }

        /**
         * @return int
         */
        public function getChaptersCount() {

            $count = (int) $this->_chapters_count;

            if ( $count < 0 ) {
                $count = 0;
            }

            return $count;
        }

        /**
         * @return array
         */

        public function getChapterIds() {

            return is_array( $this->_chapter_ids ) ? $this->_chapter_ids : array();

        }

        /**
         * Should we display help content?
         *
         * @return boolean
         */
        public function isShowHelp() {

            return $this->_show_help;
        }

        /**
         * @return array
         */
        public static function getActionList() {

            return self::$_action_list;
        }

        /**
         * @return boolean
         */
        public function shouldCreateCbr() {

            return $this->_create_cbr;
        }

        /**
         * @return boolean
         */
        public function shouldKeepCbrBackup() {

            return !$this->_no_cbr_backup;
        }

        /**
         * @return string
         */
        public function getUsername() {

            return trim( $this->_username );
        }

        /**
         * @param string $username
         */
        public function setUsername( $username ) {

            $this->_username = trim( $username );
        }

        /**
         * @return string
         */
        public function getPassword() {

            return trim( $this->_password );
        }

        /**
         * @param string $password
         */
        public function setPassword( $password ) {

            $this->_password = trim( $password );
        }

        /**
         * @return string
         */
        public function getChapterDelay() {

            return ValueParser::parseDelayValue($this->_chapter_delay);
        }

        /**
         * @param string $chapter_delay
         */
        public function setChapterDelay( $chapter_delay ) {

            $this->_chapter_delay = $chapter_delay;

        }
        
        /**
         * get image fetch delay
         *
         * @return int
         */
        public function getImageDelay() {

            return ValueParser::parseDelayValue($this->_image_delay);

        }

        /**
         * Set image fetch delay
         *
         * @param string $image_delay
         */
        public function setImageDelay( $image_delay ) {

            $this->_image_delay = $image_delay;
        }

    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 2:04 PM
     */

    class MangaHereChaptersList extends ChaptersList {

        private static $instance = NULL;

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

        private function __construct($data = array()) {

            if(isset($data['mangaInfo']) && ($data['mangaInfo'] instanceof MangaInfo)) {

                $this->_mangaInfo = $data['mangaInfo'];

            } else {
                consoleLineError('MangaInfo object not set!');
                exit();
            }

        }

        /**
         * Get lists
         *
         * @param $html
         *
         * @return mixed
         */

        private function _getLists( $html ) {

            preg_match_all( '%<li>\s*<span\s+class="left">(.*?)</span>\s*</li>%simx', $html, $result, PREG_PATTERN_ORDER );
            $result = $result[ 0 ];

            return $result;

        }

        /**
         * Gets chapter info
         *
         * @param string $listHtml
         *
         * @return array|bool
         */

        private function _getChapterInfo( $listHtml = '' ) {

            try {

                $chapterInfo = array(
                    'mangaInfo' => $this->_mangaInfo
                );

                $xml = simplexml_load_string( $listHtml );

                if ( $xml->count() >= 1 ) {

                    $children = $xml->children();

                    $info = $children[ 0 ];



                    $title = trim( (string) $info );

                    $chapterInfo[ 'title' ] = $title;

                    $a = $info->a;

                    if ( $a ) {
                        $url = (string) $a->attributes()[ 'href' ];

                        $chapterInfo[ 'url' ] = $url;

                        $slug = $this->_mangaInfo->getSlug();

                        $parts = explode( $slug, $url );

                        if ( count( $parts ) > 1 ) {

                            $chapter = trim( $parts[ 1 ], '/' );

                            $chapterInfo[ 'number' ] = $chapter;

                        }
                    }

                }


                return $chapterInfo;


            }
            catch ( Exception $ex ) {
                return FALSE;
            }

        }

        function getChapters( $url = '' ) {

            $chapters_url = $this->_mangaInfo->getUrl();

            if($chapters_url == '') {
                return false;
            }

            $content = Url::curlFetch($chapters_url);

            if(!$content) {
                return false;
            }

            $chapterListFragments = $this->_getLists($content);

            if(!$chapterListFragments) {
                consoleLineError('Unable to fetch chapters info from: '.$url);
                exit();
            }

            $chapters = array();

            foreach($chapterListFragments as $cf) {

                $chapterInfo = $this->_getChapterInfo($cf);

                if(!$chapterInfo) {
                    consoleLineError('Unable to fetch chapters info from: '.$url);
                    exit();
                }

                $chapters[$chapterInfo['number']] = new ChapterInfo($chapterInfo);

            }

            return $chapters;

        }
    }
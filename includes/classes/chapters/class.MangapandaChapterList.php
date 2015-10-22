<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 2:04 PM
     */

    class MangapandaChapterList extends ChaptersList {

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

        function getChapters( $url = '' ) {

            $chapters_url = $this->_mangaInfo->getUrl();

            if($chapters_url == '') {
                return false;
            }

            $content = Url::curlFetch($chapters_url);

            if(!$content) {
                return false;
            }

            $slug = $this->_mangaInfo->getSlug();

            preg_match_all('%<a.*?href="(/'.$slug.'/[^"]+)".*?>(.*?)</a>%sim', $content, $result, PREG_PATTERN_ORDER);

            $urls = $result[1];
            $titles = $result[2];

            $chapters = array();

            for ( $i = 0; $i < count( $urls ); $i += 1 ) {

                $url = $urls[$i];

                if (preg_match('%^/'.$slug.'/(.*?)$%sim', $url, $regs)) {
                    $chapter_id = $regs[1];

                    $c = new ChapterInfo([
                        'number' => $chapter_id,
                        'url' => 'http://www.mangapanda.com'.$url,
                        'title' => $titles[$i]
                    ]);

                    $chapters[$chapter_id] = $c;

                }


            }

            ksort($chapters,SORT_NUMERIC);

            return $chapters;

        }
    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 10:57 AM
     */
    class MangapandaChapterImages extends ChapterImages {

        public function __construct($data = array()) {

            if(isset($data['mangaInfo']) && ($data['mangaInfo'] instanceof MangaInfo)) {
                $this->_mangaInfo = $data['mangaInfo'];
            } else {
                consoleLineError('Manga info is required to fetch chapter images!',2);
                exit;
            }

            if(isset($data['chapterInfo']) && ($data['chapterInfo'] instanceof ChapterInfo)) {
                $this->_chapterInfo = $data['chapterInfo'];
            } else {
                consoleLineError('Chapter info is required!',2);
                exit;
            }

        }


        public function getImagePageUrls() {

            $chapterUrl = $this->_chapterInfo->getUrl();

            if(!$chapterUrl) {
                consoleLineError('Chapter url not defined!',2);
            }

            $content = Url::curlFetch($chapterUrl);

            if(!$content) {
                consoleLineError("Unable to fetch content from ".$chapterUrl,2);
            }

            $result = array();

            preg_match_all(
                $this->getImageLinkRegex(),
                $content,
                $result,
                PREG_PATTERN_ORDER
            );

            $urls = $result[1];
            $numbers = $result[2];

            $imageInfo = array();

            if(is_array($urls) && is_array($numbers)) {

                $loopStart = 0;
                $loopEnd = count( $urls );

                for ( $i = $loopStart; $i < $loopEnd; $i += 1 ) {

                    $imageInfo[] = new ImageInfo(array(
                        'number' => $numbers[$i],
                        'pageUrl' => 'http://www.mangapanda.com'.$urls[$i]
                    ));

                }

            }

            return $imageInfo;
        }

        public function getImageLinkRegex() {
            return '%<option.*?value="([^"]+)".*?>(.*?)</option>%sim';
        }
    }
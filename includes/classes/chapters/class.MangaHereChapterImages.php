<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 10:57 AM
     */
    class MangaHereChapterImages extends ChapterImages {

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

            $html = '';

            if (preg_match('%(<select\s+class="wid60"[^>]+>.*?</select>)%simx', $content, $regs)) {
                $html = $regs[1];
            }

            if(!$html) {
                consoleLineError('Unable to fetch chapter image urls from: '.$chapterUrl);
                exit();
            }

            try {

                $xml = simplexml_load_string($html);

                $imageInfo = array();

                foreach($xml->option as $o) {

                    $number = (string)$o;
                    $url = (string)$o->attributes()['value'];

                    $imageInfo[] = new ImageInfo(array(
                        'number' => $number,
                        'pageUrl' => $url
                    ));
                }

                return $imageInfo;

            }
            catch(Exception $ex) {

                consoleLineError('Unable to fetch chapter image urls from: '.$chapterUrl);
                exit();

            }

        }

        public function getImageLinkRegex() {
            return '';
        }
    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 12:49 PM
     */
    class MangaPandaImageScrapper extends ImageScrapper {

        function __construct($data = array()) {

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

            if(isset($data['images']) && is_array($data['images'])) {
                $this->_images = $data['images'];
            } else {
                consoleLineError('Image list not defined!',2);
                exit;
            }

        }

        /**
         * Fetch images
         */

        public function fetchImages() {

            if(!is_array($this->_images) || count($this->_images) == 0) {
                consoleLineError('No image to fetch!');
            }

            $chapterImageDir = $this->_mangaInfo->getOutputDir().'images/'.$this->_chapterInfo->getNumber().'/';

            if(!is_dir($chapterImageDir)) {

                if(!mkdir($chapterImageDir,0777,true)) {
                    consoleLineError("Unable to create chapter image dir!");
                    exit();
                }

            }

            $totalImages = count($this->_images);
            $totalFetched = 0;

            /**
             * @var ImageInfo $imgInfo
             */

            foreach($this->_images as $imgInfo) {

                if(!($imgInfo instanceof ImageInfo)) {
                    consoleLineError("Incorrect image info!");
                    exit();
                }



                $imgFileName = $this->_mangaInfo->getSlug().
                    '-'.
                    str_pad($this->_chapterInfo->getNumber(),6,'0',STR_PAD_LEFT).
                    '-'.
                    str_pad($imgInfo->getNumber(),3,'0',STR_PAD_LEFT).
                    '.jpg';

                $destImagePath = $chapterImageDir.$imgFileName;

                /* Kinda for backward compatibility */

                $imgFileName1 = $imgInfo->getNumber().'.jpg';
                $destImagePath1 = $chapterImageDir.$imgFileName1;

                if(file_exists($destImagePath1)) {
                    copy($destImagePath1,$destImagePath);

                    unlink($destImagePath1);
                }


                if(file_exists($destImagePath)) {

                    $totalFetched += 1;

                    consoleLineBlue("[{$totalFetched}/$totalImages] ".$destImagePath);

                    continue;
                }

                $imageUrl = $this->getImageUrl($imgInfo->getPageUrl());

                if($imageUrl == '') {
                    consoleLineError($destImagePath);
                    continue;
                }

                $imageData = Url::curlFetch($imageUrl);

                if(!$imageData) {
                    consoleLineError($destImagePath);
                    continue;
                } else {

                    $totalFetched += 1;

                    consoleLineSuccess("[{$totalFetched}/$totalImages] ".$destImagePath);

                    file_put_contents($destImagePath,$imageData);


                }

            }

            if($totalFetched == $totalImages) {
                consoleLinePurple("Images fetched: {$totalFetched}/$totalImages");
            } else {
                consoleLineError("Images fetched: {$totalFetched}/$totalImages");
            }


            if($totalImages == $totalFetched) {

                consoleLinePurple('Chapter completely fetched!');

                $mangaStatus = MangaStatus::getInstance();

                $completedChapters = $mangaStatus->getCompletedChaptersList();

                $completedChapters[$this->_chapterInfo->getNumber()] = $this->_chapterInfo;

                $mangaStatus->updateCompletedChaptersList($completedChapters);

                consoleLinePurple("Chapter [".$this->_chapterInfo->getTitle()."] is now set as completed!");

                if(ArgumentsList::getInstance()->shouldCreateCbr()) {

                    /* Create CBR */

                    $cbrCreator = new CbrCreator(array(
                        'mangaInfo' => $this->_mangaInfo,
                        'chapterInfo' => $this->_chapterInfo
                    ));

                    $cbrCreator->setPrintRarOutput(false);

                    $cbrCreator->createCbr();

                }




            } else {

                $mangaStatus = MangaStatus::getInstance();

                $partialChapters = $mangaStatus->getPartialChaptersList();

                $partialChapters[$this->_chapterInfo->getNumber()] = $this->_chapterInfo;

                $mangaStatus->updatePartialChaptersList($partialChapters);

                consoleLineBlue('Chapter completed partially!');
                consoleLineBlue("Chapter ".$this->_chapterInfo->getTitle()." is set as partially completed!");
            }



        }

        public function getImageUrl($pageUrl = '') {

            $pageUrl = trim($pageUrl);

            if($pageUrl == '') {
                consoleLineError("Iamge page url is empty!");
                exit();
            }

            $content = Url::curlFetch($pageUrl);

            $regs = array();

            if (preg_match('/<img.*?id="img".*?src="([^"]+)".*?>/sim', $content, $regs)) {
                $result = $regs[1];
            } else {
                $result = "";
            }

            return trim($result);

        }
    }
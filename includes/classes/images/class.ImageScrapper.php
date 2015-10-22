<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 10:52 AM
     */
    abstract class ImageScrapper {

        /**
         * @var MangaInfo $_mangaInfo
         */

        protected $_mangaInfo = null;

        /**
         * @var ChapterInfo $_chapterInfo
         */

        protected $_chapterInfo = null;

        protected $_images = null;

        public abstract function fetchImages();

        public abstract function getImageUrl($pageUrl = '');

    }
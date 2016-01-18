<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 10:53 AM
     */
    abstract class ChapterImages {

        /**
         * @var MangaInfo $_mangaInfo
         */

        protected $_mangaInfo = null;

        /**
         * @var ChapterInfo $_chapterInfo
         */

        protected $_chapterInfo = null;

        public abstract function getImagePageUrls();

        public abstract function getImageLinkRegex();

    }
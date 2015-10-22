<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 11:44 AM
     */

    function fetch_chapter_images() {

    }

    function getChapterPageList($url = '') {

        $url = trim($url);

        $content = fetch_url_content($url);

        if(!$content) {
            return false;
        }

    }
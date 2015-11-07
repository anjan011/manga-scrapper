<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/7/15
     * Time: 8:04 PM
     */
    class UrlParser {

        public static function parseUrl($url = '') {

            $url = trim($url);

            if($url == '') {
                return false;
            }

            if(!Validator::isValidHttpUrl($url)) {
                return false;
            }

            $urlParts = parse_url($url);

            if(!$urlParts) {
                return false;
            }

            $domain = $urlParts['host'];
            $path = $urlParts['path'];

            $source = MangaSourceList::getInstance()->getSourceByDomain($domain);

            if(!$source) {
                return false;
            }

            $sourceKey = $source['key'];

            $souceClassPrefix = $source['classPrefix'];

            $sourceClassName = "MangaSource{$souceClassPrefix}";

            $mangaData = false;

            if(class_exists($sourceClassName)) {

                $objSourceClass = new $sourceClassName();

                if(method_exists($objSourceClass,'getMangaDataFromPath')) {
                    $mangaData = $objSourceClass->getMangaDataFromPath($path);
                }

            }

            if(!$mangaData) {
                return false;
            }

            if(!isset($mangaData['slug']) || trim($mangaData['slug']) == '') {
                return false;
            }

            return array(
                'source' => $sourceKey,
                'slug' => $mangaData['slug'],
                'chapter' => $mangaData['chapter']
            );

        }

    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 11/7/15
     * Time: 8:04 PM
     */
    class Validator {

        /**
         * Check if given string is a valid http:// or https:// url
         *
         * @param string $string
         *
         * @return bool
         */

        public static function isValidHttpUrl( $string ) {

            return preg_match( '/\A(?:^(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]$)\Z/sim', $string ) > 0;

        }

        public static function isValidImageData( $imageData = null) {

            if(!$imageData) {
                return false;
            }

            $imageInfo = getimagesizefromstring($imageData);

            return !($imageInfo === false);

        }

    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/21/15
     * Time: 11:21 AM
     */
    class Formatter {

        /**
         * Formats a byte value to show by highest possible unit only.
         * E.g. 1024 Bytes = 1KB
         *
         * @param int $size_in_bytes
         *
         * @return string
         */

        function formattedSize( $size_in_bytes ) {

            $size_in_bytes = (int) $size_in_bytes;

            if ( $size_in_bytes <= 0 ) {
                return '0B';
            }

            $_kb = 1024;
            $_mb = $_kb * 1024;
            $_gb = $_mb * 1024;

            if ( $size_in_bytes / $_gb >= 1 ) {
                return sprintf( "%.2fGB", $size_in_bytes / $_gb );
            }
            else if ( $size_in_bytes / $_mb >= 1 ) {
                return sprintf( "%.2fMB", $size_in_bytes / $_mb );
            }
            else if ( $size_in_bytes / $_kb >= 1 ) {
                return sprintf( "%.2fKB", $size_in_bytes / $_kb );
            }
            else {
                return sprintf( "%dB", $size_in_bytes );
            }
        }

        

    }
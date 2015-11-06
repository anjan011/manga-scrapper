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

        /**
         * Converts a time difference in seconds to human readable format.
         *
         * This function takes a time difference in seconds and converts it into human readable format
         * by breaking the difference into time units like year, month, day, hour, minute and seconds.
         * Example: [4820] => [1 hour 20 minutes 20 seconds]. The difference is always considered a
         * positive number or 0.
         *
         * @param int   $diffInSeconds Time difference in seconds, always a positive number or 0
         * @param array $customLabels  Custom labels, incase different label for time units are required
         * @param bool  $pad_numbers   Pad numbers by prepending 0 to make the output fixed width
         *
         * @return string
         */

        public static function formattedTimeDifference( $diffInSeconds = 0, $customLabels = array(), $pad_numbers = FALSE ) {

            $diffInSeconds = (int) abs( $diffInSeconds );

            if ( $diffInSeconds == 0 ) {
                return '';
            }

            $units = array(
                'year'   => array(
                    'label'       => ' year',
                    'threshold'   => 86400 * 365,
                    'customLabel' => FALSE,
                ),
                'month'  => array(
                    'label'       => ' month',
                    'threshold'   => 86400 * 30,
                    'customLabel' => FALSE,
                ),
                'day'    => array(
                    'label'       => ' day',
                    'threshold'   => 86400,
                    'customLabel' => FALSE,
                ),
                'hour'   => array(
                    'label'       => ' hour',
                    'threshold'   => 3600,
                    'customLabel' => FALSE,
                ),
                'minute' => array(
                    'label'       => ' minute',
                    'threshold'   => 60,
                    'customLabel' => FALSE,
                ),

                'second' => array(
                    'label'       => ' second',
                    'threshold'   => 1,
                    'customLabel' => FALSE,
                ),
            );

            # check for custom labels

            if ( is_array( $customLabels ) && count( $customLabels ) > 0 ) {

                foreach ( $units as $k => &$unit ) {

                    if ( isset($customLabels[ $k ]) ) {

                        $newLabel = trim( $customLabels[ $k ] );

                        if ( $newLabel != '' ) {
                            $unit[ 'label' ] = $newLabel;
                            $unit[ 'customLabel' ] = TRUE;
                        }
                    }

                }

            }

            $temp = [ ];

            foreach ( $units as $k => $u ) {

                $label = $u[ 'label' ];

                $val = floor( $diffInSeconds / $u[ 'threshold' ] );

                if ( $val > 0 ) {

                    if ( $pad_numbers ) {

                        $val = sprintf( "%02d", $val );

                    }

                    $temp[] = $val.$label.((!$u[ 'customLabel' ] && $val > 1) ? 's' : '');
                }

                $diffInSeconds = $diffInSeconds % $u[ 'threshold' ];

            }

            return join( ' ', $temp );

        }

    }
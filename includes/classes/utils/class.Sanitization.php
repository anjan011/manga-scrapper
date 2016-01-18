<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 5:45 PM
     */
    class Sanitization {

        /**
         * Strips non-word character sequence from given string, with optional case transform
         *
         * @param        $sentence
         * @param string $word_seperator
         * @param string $change_case
         *
         * @return mixed|string
         */

        public static function stripNonwordCharachters( $sentence, $word_seperator = '-', $change_case = 'none' ) {

            $result = preg_replace( '/\W+/sim', ' ', $sentence );

            $result = trim( $result );

            $result = str_ireplace( ' ', $word_seperator, $result );

            switch ( $change_case ) {
                case 'lower':
                    $result = strtolower( $result );
                    break;
                case 'upper':
                    $result = strtoupper( $result );
                    break;
                case 'none':
                default:
                    break;
            }

            return $result;
        }

        /**
         * Generates a safe file name, by stripping all non-word character sequences and making it lower case.
         *
         * @param string $file_name The file name
         *
         * @return mixed|null|string
         */

        public static function sanitizedFileName( $file_name ) {

            $file_name = trim( $file_name );

            if ( $file_name === NULL || strlen( $file_name ) == 0 ) {
                return NULL;
            }

            $f_name = pathinfo( $file_name, PATHINFO_FILENAME );
            $f_ext = pathinfo( $file_name, PATHINFO_EXTENSION );

            $f_name = self::stripNonwordCharachters( $f_name, '-', 'lower' );

            $f_ext = strtolower( $f_ext );

            if ( strlen( $f_ext ) > 0 ) {
                $f_name .= '.'.$f_ext;
            }

            return $f_name;
        }

    }
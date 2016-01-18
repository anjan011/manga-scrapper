<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 1:55 PM
     */
    class Input {

        /**
         * Get array value.
         *
         * It takes the array and a key name or a key path to access element in multidimensional array
         *
         * @param array  $array   The array to conduct the search on
         * @param string $key     The Key name or key path (a/b/c/d)
         * @param mixed  $default The default value
         * @param null   $callback A callback fucntion name to process the output further
         *
         * @return mixed
         */

        public static function array_value( $array, $key, $default = NULL ,$callback = null) {

            if ( !is_array( $array ) ) {
                return $default;
            }

            $key = trim( trim( $key ), '/' );

            $parts = explode( '/', $key );

            foreach ( $parts as $p ) {

                $array = isset($array[ $p ]) ? $array[ $p ] : NULL;

                if ( $array === NULL ) {
                    return $default;
                }
            }

            if(is_callable($callback)) {
                return call_user_func($callback,$array);
            }

            return $array;
        }

        /**
         * Array value as int
         *
         * @param      $array
         * @param      $key
         * @param int  $default
         * @param null $callback
         *
         * @return int
         */

        public static function array_value_as_int( $array, $key, $default = 0 ,$callback = null) {

            return (int)self::array_value($array,$key,$default,$callback);

        }

        /**
         * Array value as float
         *
         * @param      $array
         * @param      $key
         * @param int  $default
         * @param null $callback
         *
         * @return float
         */

        public static function array_value_as_float( $array, $key, $default = 0 ,$callback = null) {

            return (float)self::array_value($array,$key,$default,$callback);

        }

        /**
         * Directly prints/returns a value from $_POST
         *
         * @param string $key     The key, can be a key path, like "data/name"
         * @param string $default The default value, if the value is null
         * @param mixed  $return  Should return?
         *
         * @param null   $callback
         *
         * @return mixed
         */

        public static function post( $key, $default = NULL, $return = FALSE ,$callback = null) {

            $data = self::array_value( $_POST, $key, $default ,$callback);

            if ( $return ) {
                return $data;
            }
            else {
                echo $data;
            }
        }

        /**
         * Directly prints/returns a value from $_GET
         *
         * @param string $key     The key, can be a key path, like "data/name"
         * @param string $default The default value, if the value is null
         * @param mixed  $return  Should return?
         *
         * @param null   $callback
         *
         * @return mixed
         */

        public static function get( $key, $default = NULL, $return = FALSE ,$callback = null) {

            $data = self::array_value( $_GET, $key, $default,$callback );

            if ( $return ) {
                return $data;
            }
            else {
                echo $data;
            }
        }

        /**
         * Directly prints/returns a value from $_SESSION
         *
         * @param string $key     The key, can be a key path, like "data/name"
         * @param string $default The default value, if the value is null
         * @param mixed  $return  Should return?
         *
         * @param null   $callback
         *
         * @return mixed
         */

        public static function session( $key, $default = NULL, $return = FALSE ,$callback = null) {

            $data = self::array_value( $_SESSION, $key, $default ,$callback);

            if ( $return ) {
                return $data;
            }
            else {
                echo $data;
            }
        }

        /**
         * Directly prints/returns a value from $_SERVER
         *
         * @param string $key     The key, can be a key path, like "data/name"
         * @param string $default The default value, if the value is null
         * @param mixed  $return  Should return?
         *
         * @param null   $callback
         *
         * @return mixed
         */

        public static function server( $key, $default = NULL, $return = FALSE ,$callback = null) {

            $data = self::array_value( $_SERVER, $key, $default ,$callback);

            if ( $return ) {
                return $data;
            }
            else {
                echo $data;
            }
        }

        /**
         * Directly prints/returns a value from $_COOKIE
         *
         * @param string $key     The key, can be a key path, like "data/name"
         * @param string $default The default value, if the value is null
         * @param mixed  $return  Should return?
         *
         * @param null   $callback
         *
         * @return mixed
         */

        public static function cookie( $key, $default = NULL, $return = FALSE ,$callback = null) {

            $data = self::array_value( $_COOKIE, $key, $default ,$callback);

            if ( $return ) {
                return $data;
            }
            else {
                echo $data;
            }
        }

    }
<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/18/15
     * Time: 9:58 PM
     */

    /**
     * Get array value.
     *
     * It takes the array and a key name or a key path to access element in multidimensional array
     *
     * @param array  $array   The array to conduct the search on
     * @param string $key     The Key name or key path (a/b/c/d)
     * @param mixed  $default The default value
     *
     * @return mixed
     */

    function __ARRAY_VALUE( $array, $key, $default = NULL ) {

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

        return $array;
    }

    /**
     * Directly prints/returns a value from $_POST
     *
     * @param string $key     The key, can be a key path, like "data/name"
     * @param string $default The default value, if the value is null
     * @param mixed  $return  Should return?
     *
     * @return mixed
     */

    function __POST( $key, $default = NULL, $return = FALSE ) {

        $data = __ARRAY_VALUE( $_POST, $key, $default );

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
     * @return mixed
     */

    function __GET( $key, $default = NULL, $return = FALSE ) {

        $data = __ARRAY_VALUE( $_GET, $key, $default );

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
     * @return mixed
     */

    function __SESSION( $key, $default = NULL, $return = FALSE ) {

        $data = __ARRAY_VALUE( $_SESSION, $key, $default );

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
     * @return mixed
     */

    function __SERVER( $key, $default = NULL, $return = FALSE ) {

        $data = __ARRAY_VALUE( $_SERVER, $key, $default );

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
     * @return mixed
     */

    function __COOKIE( $key, $default = NULL, $return = FALSE ) {

        $data = __ARRAY_VALUE( $_COOKIE, $key, $default );

        if ( $return ) {
            return $data;
        }
        else {
            echo $data;
        }
    }

    function fetch_url_content($url = '',$http_post = false,$postData = array()) {

        $url = trim($url);

        if($url == '') {
            return false;
        }

        # ========================================================
        # Curl get/post to a specific url
        # ========================================================


        $ch = curl_init();

        if ( $ch ) {
            # init success! Now, set options

            $options = array
            (
                CURLOPT_URL            => $url,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_FOLLOWLOCATION => true,
            );

            if($http_post) {
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $postData;
            }

            curl_setopt_array( $ch, $options );

            $res = curl_exec( $ch );

            if ( curl_errno( $ch ) == 0 ) {
                # request successful! Response is in $res

                curl_close( $ch );

                return $res;
            }
            else {
                # request failed!

                curl_close( $ch );

                return false;
            }


        }
        else {
            # curl init failed

            return false;
        }

    }


    /**
     * get chapters
     *
     * @param $config
     *
     * @return array|bool
     */

    function get_chapters($config = array()) {

        $site = __ARRAY_VALUE($config,'site');

        if($site == 'mangapanda') {
            return get_chapters_from_mangapanda($config);
        }

        return false;

    }

    function get_chapters_from_mangapanda($config = array()) {

        $chapters_url = trim(__ARRAY_VALUE($config,'chapters_url',''));

        if($chapters_url == '') {
            return false;
        }

        $content = fetch_url_content($chapters_url);

        if(!$content) {
            return false;
        }

        $slug = __ARRAY_VALUE($config,'slug');

        preg_match_all('%<a.*?href="(/'.$slug.'/[^"]+)".*?>(.*?)</a>%sim', $content, $result, PREG_PATTERN_ORDER);

        $urls = $result[1];
        $titles = $result[2];

        $chapters = array();

        for ( $i = 0; $i < count( $urls ); $i += 1 ) {

            $url = $urls[$i];

            if (preg_match('%^/'.$slug.'/(.*?)$%sim', $url, $regs)) {
                $chapter_id = $regs[1];

                $chapters[$chapter_id] = array(
                    'url' => 'http://www.mangapanda.com'.$url,
                    'title' => $titles[$i]
                );

            }


        }

        ksort($chapters,SORT_NUMERIC);

        return $chapters;

    }

    function consoleLineSuccess($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo ConsoleColors::coloredText($message,ConsoleColors::COLOR_GREEN).str_repeat(PHP_EOL,$newlines);
    }

    function consoleLineError($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo ConsoleColors::coloredText($message,ConsoleColors::COLOR_RED).str_repeat(PHP_EOL,$newlines);
    }

    function consoleLineInfo($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo ConsoleColors::coloredText($message,ConsoleColors::COLOR_CYAN).str_repeat(PHP_EOL,$newlines);
    }

    function consoleLinePurple($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo ConsoleColors::coloredText($message,ConsoleColors::COLOR_PURPLE).str_repeat(PHP_EOL,$newlines);
    }

    function consoleLineBlue($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo ConsoleColors::coloredText($message,ConsoleColors::COLOR_BLUE).str_repeat(PHP_EOL,$newlines);
    }

    function consoleLine($message = '',$newlines = 1) {

        $newlines = (int)$newlines;

        if($newlines < 0) {
            $newlines = 0;
        }

        echo $message.str_repeat(PHP_EOL,$newlines);
    }

    /**
     * Pad a string on right with spaces
     *
     * @param int    $text
     * @param string $length
     *
     * @return string
     */

    function __pad_space_right($text = 0,$length = '') {

        $length = (int)$length;

        return str_pad($text,$length,' ',STR_PAD_RIGHT);
    }
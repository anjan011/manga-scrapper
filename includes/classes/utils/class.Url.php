<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 5:53 PM
     */
    class Url {

        public static function curlFetch($url = '',$http_post = false,$postData = array()) {

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

    }
<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/24/15
     * Time: 5:26 PM
     */
    class ArgumentParser {

        /**
         * Gets list of CLI arguments, exculding the first argument which is
         * the script name itself
         *
         * @return array
         */

        public static function getCliArguments() {

            return isset($_SERVER['argv']) ? array_slice($_SERVER['argv'],1) : array();

        }

        /**
         * parses the argument and prepares a simple associative array
         *
         * @param array $argsData
         *
         * @return array
         */

        public static function parseAndPrepareArgumentsArray($argsData = array()) {

            $argsData = is_array($argsData) ? $argsData : array();

            if(empty($argsData)) {
                return array();
            }

            $data = array();

            foreach($argsData as $ad) {

                $ad = trim($ad);

                if($ad == '' || strpos($ad,'--') !== 0) {
                    continue;
                }

                $temp = explode('=',$ad,2);

                $argName = isset($temp[0]) ? trim($temp[0]) : '';
                $argValue = isset($temp[1]) ? trim($temp[1]) : '';

                $data[str_replace('--','',$argName)] = $argValue;

            }

            return $data;
        }

        /**
         * Parses cli arguments into an associative array
         *
         * @return array
         */

        public static function prepareCliArguments() {

            return self::parseAndPrepareArgumentsArray(self::getCliArguments());

        }

    }
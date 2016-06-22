<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 6/22/16
     * Time: 12:50 PM
     */
    class ValueParser {

        public static function parseDelayValue($delay) {

            if ( empty($delay) ) {
                return 0;
            }

            if ( preg_match( '/\A^\d+$\z/m', $delay ) ) {
                return (int) $delay;
            }
            else if ( preg_match( '/\A^(\d+)\s*,\s*(\d+)$\z/m', trim( $delay ) ) ) {

                $regs = array();

                preg_match('/^(\d+)\s*,\s*(\d+)$/m', trim( $delay ), $regs);



                $start = (int)$regs[1];
                $end = (int)$regs[2];

                if ($start == $end) {
                    return $start;
                }

                if($start > $end) {

                    $temp = $start;
                    $start = $end;
                    $end = $temp;

                }

                return mt_rand($start,$end);

            }

            return 0;

        }

    }
<?php
/*
 * JavaScript Pretty Date
 * Copyright (c) 2008 John Resig (jquery.com)
 * Licensed under the MIT license.
 */
defined('ABSPATH') || exit;

if (!class_exists('Date_Difference')) {
// Ported to PHP >= 5.1 by Zach Leatherman (zachleat.com)
// Slight modification denoted below to handle months and years.
    class Date_Difference
    {
        public static function getStringResolved($date, $compareTo = NULL)
        {
            if (!is_null($compareTo)) {
                $compareTo = new DateTime($compareTo);
            }
            return self::getString(new DateTime($date), $compareTo);
        }

        public static function getString(DateTime $date, DateTime $compareTo = NULL)
        {
            if (is_null($compareTo)) {
                $compareTo = new DateTime('now');
            }
            $diff = $compareTo->format('U') - $date->format('U');
            $dayDiff = floor($diff / 86400);

            if (is_nan($dayDiff) || $dayDiff < 0) {
                return '';
            }

            if ($dayDiff == 0) {
                if ($diff < 60) {
                    return 'Just now';
                } elseif ($diff < 120) {
                    return '1 minute ago';
                } elseif ($diff < 3600) {
                    return floor($diff / 60) . ' minutes ago';
                } elseif ($diff < 7200) {
                    return '1 hour ago';
                } elseif ($diff < 86400) {
                    return floor($diff / 3600) . ' hours ago';
                }
            } elseif ($dayDiff == 1) {
                return 'Yesterday';
            } elseif ($dayDiff < 7) {
                return $dayDiff . ' days ago';
            } elseif ($dayDiff == 7) {
                return '1 week ago';
            } elseif ($dayDiff < (7 * 6)) { // Modifications Start Here
                // 6 weeks at most
                return ceil($dayDiff / 7) . ' weeks ago';
            } elseif ($dayDiff < 365) {
                return ceil($dayDiff / (365 / 12)) . ' months ago';
            } else {
                $years = round($dayDiff / 365);
                return $years . ' year' . ($years != 1 ? 's' : '') . ' ago';
            }
        }


        /*=======================================================================
       * multipleExplode HKM HESKEMO MOD. DEVELOPMENT
       *=======================================================================*/

        public static function multipleExplode($delimiters = array(), $string = '')
        {
            $mainDelim = $delimiters[count($delimiters) - 1];
            // dernier
            array_pop($delimiters);
            foreach ($delimiters as $delimiter) {
                $string = str_replace($delimiter, $mainDelim, $string);
            }
            $result = explode($mainDelim, $string);
            return $result;
        }

        /**
         * day and time count
         * @param string $str
         * @return DateTime
         */
        static public function western_way_day_count($str = "2013-1-8")
        {
            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($str);
            //  $d1=new DateTime("2012-07-08 11:14:15.638276");
            //   $d2=new DateTime("2012-07-08 11:14:15.889342");
            $diff = $datetime2->diff($datetime1);

            $output = "";
            if ($diff->y > 0) {
                return $output = $diff->y . " year";
            } elseif ($diff->m > 0) {
                return $output = $diff->m . " months";
            } elseif ($diff->d > 0) {
                return $output = $diff->d . " days";
            } elseif ($diff->h > 0) {
                return $output = $diff->h . " hours";
            } elseif ($diff->i > 0) {
                return $output = $diff->i . " minutes";
            } elseif ($diff->s > 0) {
                return $output = $diff->s . " seconds";
            }

        }

        /**
         * asian day and time count
         * @param string $str
         * @return DateTime
         */
        static public function chinese_way_day_count($str = "2013年1月8日")
        {
            //given this time stamp - 2013年1月8日
            $timedate = self::multipleExplode(array("年", "月", "日"), $str);
            //the result will be
            //               year        month     day
            //Array ( [0] => 2013 [1] => 1 [2] => 8 [3] => )
            $timedate = $timedate[0] . "-" . $timedate[1] . "-" . $timedate[2];

            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($timedate);

            return $datetime2 - $datetime1;

        }

        public static function interpret($zero, $one, $two, $result)
        {
            if (intval($result) == 0) {
                return $zero;
            }
            if (intval($result) == 1) {
                return $one;
            }
            if (intval($result) == 2) {
                return $two;
            }
        }

        /**
         * ENGLISH OR WESTERN FORMAT
         * TO FIND OUT IF THIS TIME IS ALREADY EXPIRED OR NOT
         * @param string $str
         * @return bool|int
         */
        static public function western_time_past_event($str = "2013-1-8")
        {
            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($str);
            //what is going on now here
            if ($datetime1 == $datetime2) {
                return 0;
            } else if ($datetime1 < $datetime2) {
                //what is going on the coming event- -- FUTURE EVENT
                return 1;
            } else if ($datetime1 > $datetime2) {
                //what has been happened in the past.  -- PAST EVENT
                return 2;
            }
            return FALSE;
        }

        /**
         * CHINESE OR ASIA FORMAT
         * TO FIND OUT IF THIS TIME IS ALREADY EXPIRED OR NOT
         * @param string $str
         * @return bool|int
         */
        static public function chinese_time_past_event($str = "2013年1月8日")
        {
            //given this time stamp - 2013年1月8日
            $timedate = self::multipleExplode(array("年", "月", "日"), $str);
            //the result will be
            //               year        month     day
            //Array ( [0] => 2013 [1] => 1 [2] => 8 [3] => )
            $timedate = $timedate[0] . "-" . $timedate[1] . "-" . $timedate[2];
            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($timedate);

            //what is going on now here
            if ($datetime1 == $datetime2) {
                return 0;
            } else if ($datetime1 < $datetime2) {
                //what is going on the coming event
                return 1;
            } else if ($datetime1 > $datetime2) {
                //what has been happened in the past.
                return 2;
            }
            return FALSE;
        }

        /**
         * find the age from the birthday!
         * @param $birthday
         * @return bool|string
         */
        public static function findAge($birthday)
        {
            $birthDate = explode("/", $birthday);
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                ? ((date("Y") - $birthDate[2]) - 1)
                : (date("Y") - $birthDate[2]));
            unset($birthDate);
            unset($birthday);
            return $age;
        }
    }
}
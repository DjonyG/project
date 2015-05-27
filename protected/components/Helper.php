<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 02.08.12
 * Time: 12:10
 */

class Helper {

    public static function getPrevMonth($month, $displacement)
    {
        $displacement = $displacement % 12;
        $month = $month - $displacement;
        if($month < 1) $month += 12;

        return $month;
    }

    /**
     * Clean UTF-8 strings
     *
     * Ensures strings are UTF-8
     *
     * @param   string
     * @return  string
     */
    public static function clean_utf_string($str)
    {
        if(($encoding = mb_detect_encoding($str)))
            $str =  mb_convert_encoding($str, 'UTF-8', $encoding);
        else
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

        return $str;
    }

    public static function speedHumanize($bytesPerSecond, $separator = ' ', $decimals = 1, $number_separator=' ')
    {
        if($bytesPerSecond > 1024*1024*1024) {
            return number_format($bytesPerSecond/1024/1024/1024, $decimals, '.', $number_separator) . $separator . 'GB/s';
        } else if($bytesPerSecond > 1024*1024) {
            return number_format($bytesPerSecond/1024/1024, $decimals, '.', $number_separator) . $separator . 'MB/s';
        } else if($bytesPerSecond > 1024) {
            return number_format($bytesPerSecond/1024, $decimals, '.', $number_separator) . $separator . 'KB/s';
        } else {
            return (int)$bytesPerSecond . $separator . 'B/s';
        }
    }

    public static function bytesHumanize($bytes, $separator = ' ', $decimals = 1, $number_separator=' ')
    {
        if($bytes > 1024*1024*1024) {
            return number_format($bytes/1024/1024/1024, $decimals, '.', $number_separator) . $separator . 'GB';
        } else if($bytes > 1024*1024) {
            return number_format($bytes/1024/1024, $decimals, '.', $number_separator) . $separator . 'MB';
        } else if($bytes > 1024) {
            return number_format($bytes/1024, $decimals, '.', $number_separator) . $separator . 'KB';
        } else {
            return (int)$bytes . $separator . 'B';
        }
    }

    public static function numberHumanize($number, $separator = ' ', $decimals = 1)
    {
        if($number > 1000*1000*1000) {
            return number_format($number/1000/1000/1000, $decimals, '.', ' ') . $separator . 'G';
        } else if($number > 1000*1000) {
            return number_format($number/1000/1000, $decimals, '.', ' ') . $separator . 'M';
        } else if($number > 1000) {
            return number_format($number/1000, $decimals, '.', ' ') . $separator . 'K';
        } else {
            return (int)$number . $separator;
        }
    }

    public static function hmac($data)
    {
        $key = Yii::app()->params['swiftSecretKey'];
        return hash_hmac('sha1', $data, $key);
    }

    public static function uniqid()
    {
        $rnd_dev=mcrypt_create_iv(6, MCRYPT_DEV_URANDOM); //need "apt-get install php5-mcrypt"
        $ord = [
            str_pad(dechex(ord(substr($rnd_dev, 0, 1))), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(ord(substr($rnd_dev, 1, 1))), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(ord(substr($rnd_dev, 2, 1))), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(ord(substr($rnd_dev, 3, 1))), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(ord(substr($rnd_dev, 4, 1))), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(ord(substr($rnd_dev, 5, 1))), 2, '0', STR_PAD_LEFT),
        ];
        return dechex(rand(0, 15)) . implode('', $ord);
    }

    public static function timeInterval($seconds, $words = false)
    {
        $h = floor($seconds / 3600);
        $i = floor(($seconds % 3600) / 60);
        $s = floor($seconds % 60);

        if ($words === true) {

            if ($h > 0 && $i > 0) {
                $res = t('app', '{n} hour|{n} hours',$h);
                $res .= ' ';
                $res .= t('app', '{n} minute|{n} minutes', $i);
            } else {
                $res = t('app', '{n} minute|{n} minutes', $i);
            }

        } else {
            $res = sprintf('%02d:%02d:%02d', $h, $i, $s);
        }

        return $res;
    }

    public static function daysRemaining($date)
    {
        $secondsRemaining = strtotime($date) - time();
        return ceil($secondsRemaining / (3600 * 24));
    }

    public static function estimatedDownload($fileSize, $rateLimit, $words = false)
    {
        if(!$rateLimit) {
            $rateLimit = 5000*1024;
        }
        $secCount =  round($fileSize / ($rateLimit));

        if ($words === true) {
            $res = self::timeInterval($secCount, true);
        } else {
            $res = self::timeInterval($secCount);
        }

        return $res;
    }

    public static function getSortDirectionClass($name)
    {
        if($name != Yii::app()->request->getParam('sortField')) {
            return '';
        }
        $sd = Yii::app()->request->getParam('sortDirection');
        if(strtolower($sd) == 'asc') return 'asc';
        if(strtolower($sd) == 'desc') return 'desc';
        return '';
    }

    public static function getHostName()
    {
        return isset($_SERVER['HTTP_HOST'])? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    }

    public static function unparse_url(array $parsed_url)
    {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    public static function usd2eur($usd)
    {
        //берем курс из кэша
        $cc = Yii::app()->cache->get('usd2eur');
        if (empty($cc)) {
            //берем курс c xe.com
            $cc = 0;
            $r = file_get_contents("http://www.xe.com/currency/eur-euro");
            if (!empty($r)) {
                preg_match("|<a.*href=.\/currencycharts\/\?from=USD\&amp\;to=EUR.*>(.*)</a>|isU", $r, $matches);
                if (!empty($matches[1])) {
                    $cc = (float)$matches[1];
                }else{
                    Yii::log('usd2eur error get currency from xe.com', CLogger::LEVEL_ERROR, 'Billing');
                }
            }
            if ($cc == 0) {
                //берем из параметров дефолтный
                $cc = Yii::app()->params['usd2eur'];
            }
            Yii::app()->cache->set('usd2eur', $cc, 3600);
        }
        return round($usd * $cc, 2);
    }

    public static function parse_url($referrer)
    {
        if (strpos($referrer, 'http') !== 0) {
            $referrer = 'http://' . $referrer;
        }
        return parse_url($referrer);
    }

    /**
     * Проверка включения тулбара Yii
     */
    public static function checkYiiToolBarIsEnabled()
    {
        foreach(Yii::app()->log->routes as $route) {
            if($route instanceof YiiDebugToolbarRoute) {
                if(in_array(Yii::app()->request->userHostAddress, $route->ipFilters)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function disableYiIDebugToolBar()
    {
        //disable YiIDebugToolBar
        foreach(Yii::app()->log->routes as $logRoute) {
            if($logRoute instanceof YiiDebugToolbarRoute) {
                $logRoute->enabled = false;
            }
        }
    }

    /**
     * Аналог MySQL функции INET_ATON. Переводит строковый ip адрес в
     * беззнаковый целый тип.
     *
     * @param $ip string Строка вида 127.0.0.1
     * @return integer Числовое представление ip адреса.
     */
    public static function aton($ip)
    {
        $int = (int)sprintf('%u', ip2long($ip));
        return $int;
    }

    /**
     * Получение случайных чисел на основе устойства /dev/random
     *
     * @param $min
     * @param $max
     * @return int
     */
    public static function random($min = 0, $max = PHP_INT_MAX)
    {
        $bytes = openssl_random_pseudo_bytes(4);
        $seed = 1;
        for($i = 0; $i < strlen($bytes); $i++) {
            $seed *= ord($bytes[$i]);
        }
        srand($seed == 1? null : $seed);
        return rand($min, $max);
    }

    public static function isEqualNetwork($ip1, $ip2)
    {
        if(strpos($ip1, '.') !== false)
            $ip1 = Helper::aton($ip1);

        if(strpos($ip2, '.') !== false)
            $ip2 = Helper::aton($ip2);

        $ip1 = $ip1 >> 8;
        $ip2 = $ip2 >> 8;

        return $ip1 == $ip2;
    }

    public static function getMonthsBetweenDateInterval($date_from, $date_to)
    {
        $start    = (new DateTime($date_from))->modify('first day of this month');
        $end      = (new DateTime($date_to))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        $data = [];
        foreach ($period as $dt) {
            $data[] = $dt->format("m");
        }

        return $data;
    }

}
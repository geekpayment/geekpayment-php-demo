<?php
/**
 * Created by PhpStorm.
 * User: yixian
 * Date: 2019-03-04
 * Time: 13:58
 */
function get_real_ip()
{
    $ip = false;
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        array_reverse($ips);
        for ($i = 0; $i < count($ips); $i++) {
            if (!preg_match("^(10|172|192)\.", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
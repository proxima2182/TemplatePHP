<?php

namespace App\Helpers;

use Exception;

class Utils
{
    static function startsWith($haystack, $needle): bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    static function endsWith($haystack, $needle): bool
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    static function isNotEmpty(string $key, array $object): bool
    {
        return isset($object[$key]) && $object[$key] != null;
    }

    static function toInt($value): int
    {
        if (gettype($value) == 'string') {
            if (strlen($value) != 0) {
                $value = intval($value);
            } else {
                $value = 1;
            }
        }
        return $value;
    }

    static function parseUrl($url)
    {
        if (Utils::startsWith($url, "http")) {
            return "javascript:openWindow('" . $url . "')";
        } else if (Utils::startsWith($url, "/image-file")) {
            return "/frame-view?url=" . $url;
        }
        return $url;
    }

    /**
     * @throws Exception
     */
    static function generateRandomString($length = 10, $characters = '0123456789'): string
    {
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

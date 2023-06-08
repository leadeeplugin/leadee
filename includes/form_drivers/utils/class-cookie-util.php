<?php

class CookiesUtil
{

    public function getCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            $value = $_COOKIE[$name];
            if (is_string($value)) {
                return $value;
            }
        }
        return null;
    }
}

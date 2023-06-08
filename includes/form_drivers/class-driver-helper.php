<?php


class DriverHelper
{

    /**
     * @return int
     */
    public static function take_page_post_id()
    {
        if (isset($_SERVER["HTTP_REFERER"])) {
            $pars_url = $_SERVER["HTTP_REFERER"];
        }
        $parsed_url = wp_parse_url(trim($pars_url));

        $slug = substr($parsed_url['path'], 1);
        if ($slug == '') $slug = '/';
        if (!isset(get_page_by_path($slug)->ID) || is_null(get_page_by_path($slug)->ID)) {
            $post_id = 0;
        } else {
            $post_id = get_page_by_path($slug)->ID;
        }
        return $post_id;
    }
}
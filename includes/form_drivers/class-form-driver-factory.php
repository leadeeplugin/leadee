<?php

class FormDriverFactory
{
    public static function create()
    {
        if (isset($_POST)) {
            $action = isset($_POST['action']) ? wp_unslash($_POST['action']) : '';
            $requested_with = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';

            switch (true) {
                case (isset($action) && strpos($action, 'nf_ajax_submit') !== false) || (isset($_POST['ninja']) && $requested_with !== 'xmlhttprequest'):
                    return new NinjaDriver();
                case (isset($action) && strpos($action, '_wpcf7') !== false) || (isset($_POST['_wpcf7']) && $requested_with !== 'xmlhttprequest'):
                    return new ContactForm7Driver();
                case (isset($action) && strpos($action, 'wpforms') !== false) || (isset($_POST['wpforms']) && $requested_with !== 'xmlhttprequest'):
                    return new WpFormsDriver();
                default:
                    return null;
            }
        }
        return null;
    }
}

<?php

class ScriptsLoader
{

    private $version;

    private $assets_path;
    private $prefix;

    public function __construct()
    {
        $this->version = LEADEE_VERSION;
        $this->assets_path = LEADEE_PLUGIN_URL . '/core/assets';
        $this->prefix = 'leadee_';
        $this->load_constants();
        wp_enqueue_script($this->prefix . 'tour_js_script', $this->assets_path . '/libs/driver/driver.min.js', $this->version, false);

        wp_enqueue_script($this->prefix . 'framework7-bundle', $this->assets_path . '/libs/framework7/framework7-bundle.min.js', $this->version, false);
        wp_enqueue_script($this->prefix . 'f7-scripts', $this->assets_path . '/js/f7-scripts.js', array('jquery'), $this->version, false);

        wp_enqueue_script($this->prefix . 'main', $this->assets_path . '/js/main.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'main', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
        $this->load_datatable_js();

        wp_enqueue_script($this->prefix . 'chart_js_script', $this->assets_path . '/libs/chartjs/chart.min.js', $this->version, false);


        $this->load_datapicker_local();

        wp_enqueue_script($this->prefix . 'moment', $this->assets_path . '/js/moment-with-locales.js', $this->version, false);

        //register css
        wp_enqueue_style($this->prefix . 'montserrat', $this->assets_path . '/style/Montserrat/montserrat.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'framework7', $this->assets_path . '/libs/framework7/framework7.bundle.min.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'air-datepicker', $this->assets_path . '/libs/dpicker/air-datepicker.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'bootstrap-glyphicons', $this->assets_path . '/libs/bootstrap/bootstrap-glyphicons.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'driver', $this->assets_path . '/libs/driver/driver.min.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'driver', $this->assets_path . '/libs/driver/driver.min.css', $this->version, false);

        wp_enqueue_style($this->prefix . 'main', $this->assets_path . '/css/main.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'datatables-custom', $this->assets_path . '/css/datatables-custom.css', $this->version, false);
        wp_enqueue_style($this->prefix . 'datatables-responsive-custom', $this->assets_path . '/css/datatables-responsive-custom.css', $this->version, false);

    }

    /* Pages */
    public function load_scripts_page_dashboard()
    {
        wp_enqueue_script($this->prefix . 'page_dashboard_script', $this->assets_path . '/js/pages/dashboard/dashboard.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'page_dashboard_script', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
        $this->load_scripts_calend();
        wp_enqueue_script($this->prefix . 'dashboard-tour', $this->assets_path . '/js/pages/dashboard/dashboard-tour.js', false);
        $this->load_last_scripts();
    }

    public function load_scripts_page_leads()
    {
        wp_enqueue_script($this->prefix . 'page_leads_script', $this->assets_path . '/js/pages/leads/leads.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'page_leads_script', 'outData', array(
            'siteUrl' => get_site_url(),
            'isEnableColumnDt' => $this->isEnableColumn('dt'),
            'isEnableColumnSource' => $this->isEnableColumn('source'),
            'isEnableColumnFirstUrlParameters' => $this->isEnableColumn('first_url_parameters'),
            'isEnableColumnDeviceBrowser' => $this->isEnableColumn('device_browser'),
            'isEnableColumnDeviceScreenSize' => $this->isEnableColumn('device_screen_size')
        ));
        $this->load_scripts_calend();
        wp_enqueue_script($this->prefix . 'leads-tour', $this->assets_path . '/js/pages/leads/leads-tour.js', false);
        $this->load_last_scripts();
    }

    public function load_scripts_page_goals()
    {
        $this->load_target_graf();
        wp_enqueue_script($this->prefix . 'page_targets_script', $this->assets_path . '/js/pages/goals/goals.js', array('jquery'), $this->version, false);
        $site_url = get_site_url();
        wp_localize_script($this->prefix . 'page_targets_script', 'outData', array(
            'siteUrl' => $site_url,
        ));
        $this->load_scripts_calend();
        wp_enqueue_script($this->prefix . 'goals-tour', $this->assets_path . '/js/pages/goals/goals-tour.js', false);
        $this->load_last_scripts();
    }


    public function load_scripts_page_leads_table_settings()
    {
        wp_enqueue_script($this->prefix . 'page_settings_script', $this->assets_path . '/js/pages/leads-table-settings/leads-table-settings.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'page_leads_script', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
        wp_enqueue_script($this->prefix . 'leads-table-settings-tour', $this->assets_path . '/js/pages/leads-table-settings/leads-table-settings-tour.js', false);
        $this->load_last_scripts();
    }

    public function load_scripts_page_goals_settings()
    {
        $this->load_target_graf();
        $this->load_datatable_js();
        wp_enqueue_script($this->prefix . 'targets-settings', $this->assets_path . '/js/pages/goals-settings/goals-settings.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'page_leads_script', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
        wp_enqueue_script($this->prefix . 'goals-settings-tour', $this->assets_path . '/js/pages/goals-settings/goals-settings-tour.js', false);
        $this->load_last_scripts();
    }


    /* Another scripts  */

    public function load_scripts_calend()
    {
        wp_enqueue_script($this->prefix . 'page_calend_script', $this->assets_path . '/js/calend.js', array('jquery'), $this->version, false);
        wp_localize_script($this->prefix . 'page_calend_script', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
    }

    public function load_last_scripts()
    {
        wp_enqueue_script($this->prefix . 'load_last_scripts', $this->assets_path . '/js/common/last-scripts.js', false);
        wp_localize_script($this->prefix . 'load_last_scripts', 'outData', array(
            'siteUrl' => get_site_url(),
        ));
    }

    private function load_target_graf()
    {
        wp_enqueue_style($this->prefix . 'page_targets_graf_target_style', $this->assets_path . '/libs/graf-target/css/graf-target.css', $this->version, false);
        wp_enqueue_script($this->prefix . 'graftarget_script', $this->assets_path . '/libs/graf-target/graf-target.js', $this->version, false);
        wp_localize_script($this->prefix . 'graftarget_script', 'dataOut', array(
            'assetsPath' => $this->assets_path,
        ));
    }

    private function isEnableColumn($column)
    {
        $functions = new LeadeeFunctions();
        return $functions->get_setting_option_value('leads-table-colums', $column) == 1;
    }

    function load_constants()
    {
        wp_enqueue_script($this->prefix . 'constants_api_constants', $this->assets_path . '/js/common/constants/api-constants.js', false);
        wp_enqueue_script($this->prefix . 'constants_api_constants', $this->assets_path . '/js/common/constants/selector-constants.js', false);
    }

    function load_datatable_js()
    {
        wp_enqueue_script($this->prefix . 'data_tables', $this->assets_path . '/js/jquery.dataTables.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->prefix . 'data_tables_select', $this->assets_path . '/libs/datatables/dataTables.select.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->prefix . 'data_tables_responsive', $this->assets_path . '/libs/datatables/dataTables.responsive.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->prefix . 'data_tables_editor', $this->assets_path . '/libs/datatables/dataTables.altEditor.free.js', array('jquery'), $this->version, false);
    }

    private function load_datapicker_local()
    {
        wp_enqueue_script($this->prefix . 'air_datepicker', $this->assets_path . '/libs/dpicker/air-datepicker.js', $this->version, false);

        $current_user = wp_get_current_user();
        $user_locale = get_user_locale($current_user->ID);
        $language = substr(strstr($user_locale, '_', true), 0, 2);

        $language_file = $this->assets_path . '/libs/dpicker/locale/' . $language . '.js';
        $absolute_path = LEADEE_PLUGIN_DIR . '/core/assets/libs/dpicker/locale/' . $language . '.js';
        if (file_exists($absolute_path)) {
            wp_enqueue_script($this->prefix . 'air-datepicker_lang', $language_file, $this->version, true);
        } else {
            wp_enqueue_script($this->prefix . 'air-datepicker_lang', $this->assets_path . '/libs/dpicker/locale/en.js', $this->version, true);
        }
    }
}

<?php

class LeadeeFunctions
{
    private $code_serp = 'serp';
    private $code_social = 'social';
    private $code_advert = 'advert';
    private $code_referal = 'referal';
    private $code_direct = 'direct';
    private $colorGraf;
    private $table_name_leads;
    private $table_name_leadee_targets;
    private $table_name_default_serp;
    private $table_name_default_social;
    private $table_name_default_advert;
    private $glob_wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->glob_wpdb = $wpdb;
        $this->colorGraf = new ColorGraf();
        $db_prefix = $this->glob_wpdb->prefix;
        $this->table_name_leads = $db_prefix . 'leadee_leads';
        $this->table_name_leadee_targets = $db_prefix . 'leadee_targets';
        $this->table_name_default_serp = $db_prefix . 'leadee_base_default_serp';
        $this->table_name_default_social = $db_prefix . 'leadee_base_default_social';
        $this->table_name_default_advert = $db_prefix . 'leadee_base_default_advert';
    }

    /**
     * @param $start
     * @param $limit
     * @param $order_by_column
     * @param $order_asc_desc
     * @param $from
     * @param $to
     * @param $filters
     * @param $search_text
     * @return mixed
     */
    public function get_filter_search_leads($start, $limit, $order_by_column, $order_asc_desc, $from, $to, $filters, $search_text)
    {
        $query = $this->take_sql_query_for_filter_and_search($from, $to, $order_by_column, $order_asc_desc, $filters, $start, $limit, $search_text);
        return $this->glob_wpdb->get_results($query);
    }

    /**
     * @param $from
     * @param $to
     * @param $filters
     * @return mixed
     */
    public function get_all_filtered_leads_without_page_limit($from, $to, $filters)
    {
        $table_name = $this->table_name_leads;
        $filterSql = '';
        foreach ($filters as $filter) {
            $filterSql .= $filter['key'] . " = '" . $filter['value'] . "' AND ";
        }
        $query = "SELECT  * FROM $table_name WHERE $filterSql `dt` BETWEEN '$from' AND '$to'";
        return $this->glob_wpdb->get_results($query);
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_top_5_posts($from, $to)
    {
        $query = $this->glob_wpdb->prepare("SELECT COUNT(`id`) as count, `post_id` FROM " . $this->table_name_leads . " WHERE `dt` BETWEEN %s AND %s GROUP BY `post_id` ORDER BY count DESC LIMIT 5", $from, $to);
        return $this->glob_wpdb->get_results($query);
    }

    /**
     * @return int
     */
    public function get_total_leads()
    {
        $count = $this->glob_wpdb->get_results("SELECT COUNT(*) as count FROM " . $this->table_name_leads)[0]->count;
        return $count == null ? 0 : (int)$count;
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_total_leads_from_to($from, $to)
    {
        return $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE `dt` BETWEEN %s AND %s", $from, $to))[0]->count;
    }

    /**
     * @return mixed
     */
    public function get_total_today_leads()
    {
        return $this->glob_wpdb->get_results("SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE dt >= CURDATE()")[0]->count;
    }

    /**
     * @return mixed
     */
    public function get_total_yesterday_leads()
    {
        return $this->glob_wpdb->get_results("SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE dt >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND dt < CURDATE()")[0]->count;
    }


    /**
     * @return mixed
     */
    public function get_total_week_leads()
    {
        return $this->glob_wpdb->get_results("SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE dt >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)")[0]->count;
    }

    /**
     * @param $domain
     * @return int
     */
    public function check_domain_default_social($domain)
    {
            return $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT COUNT(*) as count FROM " . $this->table_name_default_social . " WHERE `domain` = %s", $domain))[0]->count;
    }

    /**
     * @param $domain
     * @return int
     */
    public function check_domain_default_serp($domain)
    {
        return $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT COUNT(*) as count FROM " . $this->table_name_default_serp . " WHERE `domain` = %s", $domain))[0]->count;
    }

    /**
     * @param $parameters
     * @return int
     */
    public function check_parameter_default_advert($parameters)
    {
        $count = 0;
        $table_name = $this->table_name_default_advert;
        foreach ($parameters as $parameter) {
            $parameter_explode = explode("=", $parameter);
            $count = $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT COUNT(*) as count FROM {$table_name} WHERE `parameter` = %s", $parameter_explode[0]))[0]->count;
            if ($count > 0) {
                break;
            }
        }
        return $count;
    }

    /**
     * @return mixed
     */
    public function get_last_lead()
    {
        return $this->glob_wpdb->get_results("SELECT * FROM " . $this->table_name_leads . " ORDER by id DESC limit 1");
    }


    /**
     * @param $post_type
     * @return mixed
     */
    private function get_all_forms_by_post_type($post_type)
    {
        return $this->glob_wpdb->get_results(
            $this->glob_wpdb->prepare(
                "SELECT ID, post_title FROM " . $this->glob_wpdb->prefix . "posts WHERE post_type = %s AND post_status <> 'trash'",
                $post_type
            )
        );
    }


    /**
     * @return array
     */
    public function get_all_forms_wpforms()
    {
        $data = [];
        $all_forms = $this->get_all_forms_by_post_type('wpforms');
        foreach ($all_forms as $key => $form) {
            $data[$key]['id'] = $form->ID;
            $data[$key]['type'] = 'wpforms';
            $data[$key]['title'] = ($form->post_title) ? $form->post_title : '';
        }

        return $data;
    }

    /**
     * @return array
     */
    public function get_all_forms_cf7()
    {
        $data = [];
        $all_forms = $this->get_all_forms_by_post_type('wpcf7_contact_form');
        foreach ($all_forms as $key => $form) {
            $data[$key]['id'] = $form->ID;
            $data[$key]['type'] = 'cf7';
            $data[$key]['title'] = ($form->post_title) ? $form->post_title : '';
        }
        return $data;
    }


    /**
     * @return array
     */
    public function get_all_forms_ninja()
    {
        $data = [];

        $table_name = $this->glob_wpdb->prefix . 'nf3_forms';
        $table_exists = $this->glob_wpdb->get_var("SHOW TABLES LIKE '$table_name'");

        if ($table_exists) {
            $all_forms = $this->glob_wpdb->get_results("SELECT id, title FROM $table_name");
            foreach ($all_forms as $key => $form) {
                $data[$key]['id'] = $form->id;
                $data[$key]['type'] = 'ninja';
                $data[$key]['title'] = ($form->title) ? $form->title : '';
            }
        }
        return $data;
    }

    /**
     * @param $form_type
     * @param $form_id
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_count_leads_by_form_type($form_type, $form_id, $from, $to)
    {
        $leads_count = $this->glob_wpdb->prepare("SELECT count(*) as count FROM " . $this->table_name_leads . " WHERE `dt` BETWEEN %s AND %s AND form_type = %s AND form_id = %s", $from, $to, $form_type, $form_id);
        return $this->glob_wpdb->get_results($leads_count)[0]->count;
    }

    /**
     * @param $form_type
     * @param $form_id
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_sum_leads_by_form_type($form_type, $form_id, $from, $to)
    {
        $leads_sum = $this->glob_wpdb->prepare("SELECT sum(cost) as sum FROM " . $this->table_name_leads . " WHERE dt BETWEEN %s AND %s AND form_type = %s AND form_id = %s", $from, $to, $form_type, $form_id);
        return $this->glob_wpdb->get_results($leads_sum)[0]->sum;
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_count_leads_by_range($from, $to)
    {
        $leads_count = $this->glob_wpdb->prepare("SELECT count(*) as count FROM " . $this->table_name_leads . " WHERE dt BETWEEN %s AND %s", $from, $to);
        return $this->glob_wpdb->get_results($leads_count)[0]->count;
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function get_sum_leads_by_range($from, $to)
    {
        $leads_count = $this->glob_wpdb->prepare("SELECT sum(cost) as sum FROM " . $this->table_name_leads . " WHERE dt BETWEEN %s AND %s", $from, $to);
        return $this->glob_wpdb->get_results($leads_count)[0]->sum;
    }

    /**
     * @param $target_type
     * @param $identifier
     * @return array|mixed
     */
    public function get_status_and_cost_by_target_type($target_type, $identifier)
    {
        $leads_count = $this->glob_wpdb->prepare("SELECT status, cost FROM " . $this->table_name_leadee_targets . " WHERE type = %s AND identifier = %s", $target_type, $identifier);
        $res = $this->glob_wpdb->get_results($leads_count);
        if (!sizeof($res) == 0) {
            return $res[0];
        }
        return [];
    }

    /**
     * @param $string
     * @return string
     */
    public function clean($string)
    {
        $string = strip_tags($string);
        $string = str_replace('http://', '', $string);
        $string = str_replace('https://', '', $string);
        $string = htmlspecialchars($string);
        return $string;
    }

    /**
     * @param $post_id
     * @return string
     */
    public function get_post_name_by_id($post_id)
    {
        if (intval($post_id) == 0) {
            return get_bloginfo('name');
        }
        $post_id = intval($post_id);
        $table_name = $this->glob_wpdb->prefix . 'posts';
        $query = $this->glob_wpdb->prepare("SELECT post_title FROM $table_name WHERE id = %d LIMIT 1", $post_id);
        $res = $this->glob_wpdb->get_results($query);
        if (!empty($res[0]->post_title)) {
            return $res[0]->post_title;
        } else {
            return __('Removed form =', 'leadee') . ' ' . $post_id;
        }
    }

    /**
     * @param $setting_type
     * @param $option
     * @return null
     */
    public function get_setting_option_value($setting_type, $option)
    {
        $settings_table = $this->glob_wpdb->prefix . "leadee_settings";
        $query = $this->glob_wpdb->prepare("SELECT * FROM $settings_table WHERE setting_type = %s AND `option` = %s", $setting_type, $option);
        $result = $this->glob_wpdb->get_results($query);
        if (empty($result)) {
            return null;
        }
        return $result[0]->value;
    }

    /**
     * @param $column
     * @return bool
     */
    public function isEnableColumn($column)
    {
        return $this->get_setting_option_value('leads-table-colums', $column) == 1;
    }

    /**
     * @param $type
     * @param $option
     * @param $value
     */
    public function set_setting_option_value($type, $option, $value)
    {
        $this->glob_wpdb->update($this->glob_wpdb->prefix . 'leadee_settings',
            ['value' => $value],
            ['setting_type' => $type, 'option' => $option]
        );
    }

    /**
     * @param $source_category
     * @param $day_from
     * @param $day_to
     * @return array
     */
    public function get_data_by_source_category($source_category, $day_from, $day_to)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE `source_category` = '$source_category' AND `dt` BETWEEN '$day_from' AND '$day_to'";
        $leads = $this->glob_wpdb->get_results($query);
        $result = array();
        foreach ($leads as $key => $day_res) {
            $result[] = array('count' => $day_res->count);
        }
        return $result;
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function get_main_chart_all_data($from, $to)
    {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table_name_leads . " WHERE dt BETWEEN %s AND %s";
        $leads = $this->glob_wpdb->get_results($this->glob_wpdb->prepare($sql, $from, $to));
        $result = array();
        foreach ($leads as $key => $day_res) {
            $result[] = array('count' => $day_res->count);
        }
        return $result;
    }

    /**
     * @param $from
     * @param $to
     * @param $top_limit
     * @return mixed
     */
    public function get_device_os_top($from, $to, $top_limit)
    {
        return $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT device_os, count(*) AS count FROM $this->table_name_leads WHERE dt BETWEEN %s AND %s GROUP BY device_os ORDER BY count DESC LIMIT %d", $from, $to, $top_limit));
    }

    /**
     * @param $from
     * @param $to
     * @param $top_limit
     * @return array
     */
    public function get_device_screen_size_top($from, $to, $top_limit)
    {
        $leads = $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT device_width, COUNT(*) AS 'count' FROM " . $this->table_name_leads . " WHERE dt BETWEEN %s AND %s GROUP BY device_width ORDER BY count DESC LIMIT %d", $from, $to, $top_limit));
        $result = array();
        $i = 0;
        foreach ($leads as $key => $day_res) {
            if ($day_res->count > 0) {
                $result[$i]['width'] = $day_res->device_width;
                $result[$i]['count'] = $day_res->count;
            }
            $i++;
        }
        return $result;
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function get_last_leads($limit)
    {
        return $this->glob_wpdb->get_results($this->glob_wpdb->prepare("SELECT * FROM " . $this->table_name_leads . " ORDER BY id DESC LIMIT %d", $limit));
    }

    /**
     * @param $source_domain
     * @param $parameters
     * @return string
     */
    public function get_source_category($source_domain, $parameters)
    {
        $source_category = 'direct';

        if (!empty($source_domain)) {
            if ($this->check_domain_default_serp($source_domain)) {
                $source_category = 'serp';
            } elseif ($this->check_domain_default_social($source_domain) > 0) {
                $source_category = 'social';
            } else {
                $source_category = 'referal';
            }
        }
        if ($this->check_parameter_default_advert($parameters) > 0) {
            $source_category = 'advert';
        }
        return $source_category;
    }

    /**
     * @param $type
     * @param $identifier
     * @param $cost
     * @param $status
     */
    public function save_target_setting($type, $identifier, $cost, $status)
    {
        $query_count = $this->glob_wpdb->prepare("SELECT COUNT(*) as count FROM " . $this->table_name_leadee_targets . " WHERE type = %s AND identifier = %s", $type, $identifier);
        $count_find_target = $this->glob_wpdb->get_var($query_count);

        if ($count_find_target == 0) {
            $this->create_target_settings($type, $identifier, $cost, $status);

        } else {
            $this->save_target_settings($type, $identifier, $cost, $status);
        }
    }

    /**
     * @param $post_id
     * @param $form_id
     * @param $fields
     * @param $data_arr
     * @param $form_type
     * @param $lead_cost
     */
    public function write_lead($post_id, $form_id, $fields, $data_arr, $form_type, $lead_cost)
    {
        // Assign domain variable with HTTP_HOST as a fallback
        $domain = isset($data_arr['domain']) ? $data_arr['domain'] : $_SERVER['HTTP_HOST'];

        // Sanitize input data
        $post_id = intval($post_id);
        $form_id = intval($form_id);
        $form_type = sanitize_text_field($form_type);
        $lead_cost = floatval($lead_cost);
        $source = sanitize_text_field($domain);
        $source_category = sanitize_text_field($data_arr['source_category']);
        $first_url_parameters = sanitize_text_field($data_arr['first_url_parameters']);
        $device_type = sanitize_text_field($data_arr['device_type']);
        $device_os = sanitize_text_field($data_arr['device_os']);
        $device_os_version = sanitize_text_field($data_arr['device_os_version']);
        $device_browser_name = sanitize_text_field($data_arr['device_browser_name']);
        $device_browser_version = sanitize_text_field($data_arr['device_browser_version']);
        $user_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);

        $screen_height = intval($_COOKIE['device_height']);
        $screen_width = intval($_COOKIE['device_width']);

        $current_time = gmdate('Y-m-d H:i:s');

        // Insert data into the database
        $this->glob_wpdb->insert(
            $this->table_name_leads,
            array(
                'post_id' => $post_id,
                'form_type' => $form_type,
                'form_id' => $form_id,
                'cost' => $lead_cost,
                'source' => $source,
                'source_category' => $source_category,
                'fields' => json_encode($fields, JSON_UNESCAPED_UNICODE),
                'first_url_parameters' => $first_url_parameters,
                'device_type' => $device_type,
                'device_os' => $device_os,
                'device_os_version' => $device_os_version,
                'device_browser_name' => $device_browser_name,
                'device_browser_version' => $device_browser_version,
                'device_height' => $screen_height,
                'device_width' => $screen_width,
                'user_agent' => $user_agent,
                'dt' => $current_time
            )
        );

    }

    /**
     * @param $period
     * @return array
     */
    public function get_data_main_chart($period)
    {
        $colors = [];
        foreach ($period as $p) {
            $colors[] = $p["color"];
        }
        $dataMainChart = array(
            "labels" => $this->get_labels($period),
            "data" => $this->get_main_chart_data($period),
            "colors" => $colors
        );
        return $dataMainChart;
    }

    /**
     * @param $from
     * @param $to
     * @param $top_limit
     * @return array
     */
    public function get_data_screen_size($from, $to, $top_limit)
    {
        $screens = $this->get_device_screen_size_top($from, $to, $top_limit);

        $labels = [];
        $colors_all = ["#36a2eb", "#8AC44B", "#FCC02A", "#6e62ef", "#263238", "#ddd", "#ggg"];
        $colors = [];
        $screens_count_array = [];
        $i = 0;
        foreach ($screens as $screen) {
            $labels[] = $screen['width'];
            $colors[] = $colors_all[$i];
            $screens_count_array[$i] = $screen['count'];
            $i++;
        }

        $dataScreenSize = array(
            "labels" => $labels,
            "data" => $screens_count_array,
            "colors" => $colors
        );
        return $dataScreenSize;
    }

    /**
     * @param $period
     * @return array
     */
    public function get_data_chart_source($period)
    {
        $dataChartSourceDatasets = array(
            0 => array(
                "data" => $this->get_values_for_type_source($period, $this->code_serp),
                "label" => "Search engines",
                "borderColor" => "#2a74b9",
                "fill" => false
            ),
            1 => array(
                "data" => $this->get_values_for_type_source($period, $this->code_advert),
                "label" => "Advertising",
                "borderColor" => "#8ac44c",
                "fill" => false),
            2 => array(
                "data" => $this->get_values_for_type_source($period, $this->code_social),
                "label" => "Social networks",
                "borderColor" => "#fbc02a",
                "fill" => false
            ),
            3 => array(
                "data" => $this->get_values_for_type_source($period, $this->code_direct),
                "label" => "Direct visits",
                "borderColor" => "#635e6f",
                "fill" => false
            ),
            4 => array(
                "data" => $this->get_values_for_type_source($period, $this->code_referal),
                "label" => "Website referrals",
                "borderColor" => "#d62d30",
                "fill" => false
            ),
        );
        $dataChartSource = array(
            "labels" => $this->get_labels($period),
            "datasets" => $dataChartSourceDatasets
        );

        return $dataChartSource;
    }

    /**
     * @param $days
     * @param $typeSource
     * @return array
     */
    private function get_values_for_type_source($days, $typeSource)
    {

        $typeTraficData = [];
        foreach ($days as $day) {
            $from = $day["range"]["from"];
            $to = $day["range"]["to"];
            $data = $this->get_data_by_source_category($typeSource, $from, $to);
            $count = [];
            foreach ($data as $key => $c) {
                $count[$key] = $c["count"];
            }
            $typeTraficData[] = $count[0];
        }
        return $typeTraficData;
    }

    /**
     * @param $days
     * @return array
     */
    private function get_main_chart_data($days)
    {
        $resData = [];
        foreach ($days as $day) {
            $from = $day["range"]["from"];
            $to = $day["range"]["to"];
            $data = $this->get_main_chart_all_data($from, $to);
            $count = [];
            foreach ($data as $key => $c) {
                $count[$key] = $c["count"];
            }
            $resData[] = $count[0];
        }
        return $resData;
    }

    /**
     * @param $days
     * @return array
     * @throws Exception
     */
    private function get_labels($days)
    {
        $labels = [];
        foreach ($days as $day) {
            $from = new DateTime($day["range"]["from"]);
            $labels[] = $from->format("m/d/y");
        }
        return $labels;
    }

    /**
     * @param $timezone
     * @param $limit
     * @return array
     * @throws Exception
     */
    public function get_data_new_leads($timezone, $limit)
    {
        $dataNewLeads = [];
        foreach ($this->get_last_leads($limit) as $lead) {
            $date_of_lead = new DateTime($lead->dt);
            $text = [];
            $fields = json_decode($lead->fields);

            foreach ($fields as $field) {
                $value = is_string($field->value) ? $field->value : json_encode($field->value);
                $text[] = $value;
            }
            $dataNewLeads[] = array(
                "id" => $lead->post_id,
                "dt" => $this->ago($date_of_lead, $timezone),
                "text" => implode(" ", $text)
            );
        }

        return $dataNewLeads;
    }

    /**
     * @param $date_of_lead
     * @param $timezone
     * @return string
     * @throws Exception
     */
    function ago($date_of_lead, $timezone)
    {
        $now_with_timezone = new DateTime('now', new DateTimeZone('UTC'));
        $now_time = new DateTime($now_with_timezone->format('Y-m-d H:i:s'));
        $lead_time = new DateTime($date_of_lead->format("Y-m-d H:i:s"));
        $diff = $now_time->getTimestamp() - $lead_time->getTimestamp();
        if ($diff < 60 && $diff > 0) {
            return "just now";
        }

        if ($diff < 86400) {
            if ($diff <= 3600) {
                // Mins
                $min = floor($diff / 60);

                $str = sprintf("%s%s",
                    ($min != 0) ? $min . " m " : "",
                    "ago"
                );
            } else {
                // Hours
                $hrs = floor($diff / 3600);
                $str = sprintf("%s%s",
                    ($hrs != 0) ? $hrs . " h " : "",
                    "ago"
                );
            }

            return $str;
        } else {
            ini_alter('date.timezone', $timezone);
            date_default_timezone_set($timezone);
            $when_with_timezone = $date_of_lead->setTimezone(new DateTimeZone($timezone));
            return $when_with_timezone->format("m/d/Y");
        }
    }

    /**
     * @return array
     */
    public function get_counters_data()
    {
        $counter_today = $this->get_total_today_leads();
        $counter_yesterday = $this->get_total_yesterday_leads();
        $counter_week = $this->get_total_week_leads();
        $counter_today_diff = $counter_today - $counter_yesterday;
        $current_targets_data = $this->read_current_month_targets_data();

        $data = array(
            "isSet" => true,
            "target" => array(
                "targetUser" => $current_targets_data['month-target'],
                "targetCurrent" => $current_targets_data['leads-month-sum'],
            ),
            "counters" => array(
                "counterToday" => $counter_today,
                "counterTodayDiff" => $counter_today_diff,
                "counterYesterday" => $counter_yesterday,
                "counterWeek" => $counter_week,
            )
        );
        return $data;
    }

    /**
     * @param $from
     * @param $to
     * @param $top_limit
     * @return array
     */
    public function get_os_clients_data_by_top($from, $to, $top_limit)
    {
        $getOs = $this->get_device_os_top($from, $to, $top_limit);
        $osCount = 0;
        foreach ($getOs as $os) {
            $osCount = $osCount + (int)$os->count;
        }
        return array(
            "items" => $getOs,
            "allItems" => $osCount
        );
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function get_popular_pages_data($from, $to)
    {
        $dataPopularPages = [];
        $topPosts = $this->get_top_5_posts($from, $to);
        $allPosts = $this->get_total_leads_from_to($from, $to);
        foreach ($topPosts as $post) {
            if ($post->post_id == 0) {
                $title = get_bloginfo('name');
                $urlRelative = "";
            } else {
                $title = get_the_title($post->post_id);
                $urlRelative = "";
            }
            $url = get_site_url() . "/?post_type=undefined&p=" . $post->post_id;
            if ($post->post_id == 0) {
                $url = get_site_url();
            }
            if ($allPosts != 0) {
                $percentage = round(((int)$post->count / (int)$allPosts * 100), 2);
            } else {
                $percentage = 0;
            }

            $dataPopularPages[] = array(
                "title" => $title,
                "url" => $url,
                "urlRelative" => $urlRelative,
                "count" => $post->count,
                "all" => $allPosts,
                "percent" => $percentage
            );
        }
        return $dataPopularPages;
    }

    /**
     * @param $from
     * @param $to
     * @param $timezone
     * @return array
     */
    public function get_period_data_from_calend($from, $to, $timezone)
    {
        $param = [];
        $param['from'] = $from;
        $param['to'] = $to;
        $param['out_date_format'] = "Y-m-d H:i:s";
        $param['timezone'] = $timezone;
        return $this->colorGraf->ColorDate($param);
    }

    /**
     * @param $timezone
     * @return array
     * @throws Exception
     */
    function get_last_lead_data($timezone)
    {
        $data_new_leads = array();
        $leads = $this->get_last_lead();
        if (!empty($leads)) {
            $last_lead = $leads[0];
            $date_of_lead = new DateTime($last_lead->dt);
            $text = [];
            foreach (json_decode($last_lead->fields) as $field) {
                $text[] = $field->value;
            }

            $data_new_leads = array(
                "dt" => $this->ago($date_of_lead, $timezone),
                "text" => implode(" ", $text)
            );
        }

        return $data_new_leads;
    }

    /**
     * @return array
     */
    public function read_current_month_targets_data()
    {
        $month_target = $this->get_setting_option_value('setting-target', 'month-target');
        $format_for_db = "Y-m-d";
        $first_day_month = new DateTime('first day of this month');
        $first_day_month_formatted = $first_day_month->format($format_for_db) . " 00:00:00";
        $today_formatted = date($format_for_db) . " 23:59:59";

        $leads_month_count = $this->get_count_leads_by_range($first_day_month_formatted, $today_formatted);
        $leads_month_sum = $this->get_sum_leads_by_range($first_day_month_formatted, $today_formatted);

        return ['month-target' => $month_target, 'leads-month-count' => $leads_month_count, 'leads-month-sum' => $leads_month_sum];
    }

    /**
     * Scan all user forms and save it.
     *
     * @param false $is_need_save_empty_forms
     * @return array
     */
    public function scan_all_froms($is_need_save_empty_forms = false)
    {
        $forms = array_merge($this->get_all_forms_cf7(), $this->get_all_forms_wpforms(), $this->get_all_forms_ninja());
        $result = [];
        foreach ($forms as $key => $form) {
            $status_and_cost = $this->get_status_and_cost_by_target_type($form['type'], $form['id']);

            $formId = isset($form['id']) ? $form['id'] : null;
            $formTitle = isset($form['title']) ? $form['title'] : null;
            $formType = isset($form['type']) ? $form['type'] : null;
            $status = isset($status_and_cost->status) ? $status_and_cost->status : null;
            $cost = isset($status_and_cost->cost) ? $status_and_cost->cost : null;

            $result[] = [
                'id' => $formId,
                'title' => $formTitle,
                'type' => $formType,
                'status' => $status,
                'sum' => $cost,
            ];

            if ($is_need_save_empty_forms) {
                $this->create_target_settings($form['type'], $form['id'], 1, 1);
            }
        }
        return $result;
    }

    /**
     * @param $target_table
     * @param $type
     * @param $identifier
     * @param $cost
     * @param $status
     */
    public function create_target_settings($type, $identifier, $cost, $status)
    {
        $this->glob_wpdb->query(
            $this->glob_wpdb->prepare(
                "INSERT IGNORE INTO {$this->table_name_leadee_targets} (`type`, `identifier`, `cost`, `status`) VALUES (%s, %s, %d, %d)",
                $type,
                $identifier,
                $cost,
                $status
            )
        );

    }

    /**
     * @param $target_table
     * @param $cost
     * @param $status
     * @param $type
     * @param $identifier
     */
    private function save_target_settings($type, $identifier, $cost, $status)
    {
        $this->glob_wpdb->update($this->table_name_leadee_targets,
            array(
                'cost' => $cost,
                'status' => $status
            ), array(
                'type' => $type,
                'identifier' => $identifier
            ),
            array(
                '%d',
                '%d'
            ),
            array(
                '%s',
                '%s'
            )
        );
    }

    /**
     * @param $from
     * @param $to
     * @param $order_by_column
     * @param $order_asc_desc
     * @param $filters
     * @param $start
     * @param $limit
     * @param $search_text
     * @return mixed
     */
    private function take_sql_query_for_filter_and_search($from, $to, $order_by_column, $order_asc_desc, $filters, $start, $limit, $search_text)
    {
        $allowed_columns = array('form_type', 'form_id', 'source', 'source_category', 'fields', 'first_url_parameters', 'device_type', 'device_os', 'device_os_version', 'device_browser_name', 'device_browser_version', 'device_height', 'device_width', 'user_agent', 'dt');
        $allowed_order_by = array('ASC', 'DESC');

        $search_text = esc_sql($search_text);

        if (!in_array($order_by_column, $allowed_columns)) {
            $order_by_column = 'dt';
        }
        if (!in_array(strtoupper($order_asc_desc), $allowed_order_by)) {
            $order_asc_desc = 'DESC';
        }
        $table_name = $this->table_name_leads;
        $filterSql = '';
        $values = array();
        foreach ($filters as $filter) {
            $filterSql .= $filter['key'] . " = %s AND ";
            $values[] = esc_sql($filter['value']);
        }

        $search_sql = '';
        if (strlen($search_text) > 0) {
            $search_sql_prepare = "CONCAT(form_type, form_id, source, source_category, fields, first_url_parameters," .
                "device_type, device_os, device_os_version, device_browser_name, device_browser_version, device_height, device_width, user_agent, dt) " .
                "LIKE '<search_text>' AND";
            $search_sql = str_replace('<search_text>', '%' . $search_text . '%', $search_sql_prepare);
        }

        $query = $this->glob_wpdb->prepare("SELECT * FROM $table_name WHERE $search_sql $filterSql `dt` BETWEEN %s AND %s ORDER by $order_by_column $order_asc_desc LIMIT %d, %d",
            array_merge($values, array($from, $to, $start, (int)$start + (int)$limit)));

        return $query;
    }
}

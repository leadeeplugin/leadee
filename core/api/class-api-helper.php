<?php

class LeadeeApiHelper
{
    public function __construct()
    {
        $this->functions = new LeadeeFunctions();
    }

    public function set_names_for_field_data($fields)
    {
        $fields = json_decode($fields, true);
        $data = array();
        foreach ($fields as $key => $field) {
            $data[$key] = array('field_name' => "", 'value' => $field['value']);
        }
        return $data;
    }

    public function get_form_name($form_id)
    {
        if ($form_id > 0) {
            return $this->functions->get_post_name_by_id($form_id);
        } else {
            return '';
        }
    }

    private function prepare_filter($filters)
    {
        $filtersArrPrepare = explode(";", $filters);
        $filtersArr = [];
        foreach ($filtersArrPrepare as $key => $item) {
            $itemArray = explode("#", $item);
            if (isset($itemArray[0]) && isset($itemArray[1])) {
                $filtersArr[$key]['key'] = $itemArray[0];
                $filtersArr[$key]['value'] = trim($itemArray[1]);
            }
        }
        return $filtersArr;
    }

    public function get_leads_data($from, $to, $filter, $timezone)
    {
        $filtersArr = $this->prepare_filter(urldecode($filter));
        $from = strval($from);
        $to = strval($to);
        $result = [];
        if (isset($_GET['draw'])) {
            $total = $this->functions->get_total_leads_from_to($from, $to);
            $search_value = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
            $order_column = 'dt';//TODO enable sort for next releases
            $order_dir = 'desc';//TODO enable sort for next releases
            $start = (int)$_GET['start'];
            $limit = (int)$_GET['length'];
            if ($limit == 0) {
                $limit = 25;
            }

            $leads = $this->functions->get_filter_search_leads($start, $limit, $order_column, $order_dir, $from, $to, $filtersArr, $search_value);
            $leads_no_limit = $this->functions->get_all_filtered_leads_without_page_limit($from, $to, $filtersArr);
            $filer_total = count($leads_no_limit);

            $data = [];
            if (!empty($leads)) {
                foreach ($leads as $key => $lead) {
                    $post_id = $leads[$key]->post_id;
                    $site_name = get_bloginfo('name');
                    $post_name = ($post_id == 0) ? $site_name : get_the_title($post_id);
                    $source_category = $leads[$key]->source_category;
                    $lead_data = $leads[$key];
                    $data = $this->mapData($data, $key, $lead_data, $source_category, $post_name, $timezone);
                }
            }

            $result = [
                "draw" => $_GET['draw'],
                "recordsTotal" => $total,
                "recordsFiltered" => $filer_total,
                "data" => $data
            ];
        }
        return $result;
    }

    private function mapData($data, $key, $lead_data, $source_category, $post_name, $timezone)
    {
        $data[$key]['id'] = $lead_data->id;

        $dt_from_db_in_utc0 = $lead_data->dt;
        $dt = new DateTime($dt_from_db_in_utc0, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone($timezone));
        $formatted_dt = $dt->format("Y-m-d H:i:s");
        $data[$key]['dt'] = $formatted_dt;


        $data[$key]['fields'] = json_encode($this->set_names_for_field_data($lead_data->fields));
        $data[$key]['source_category'] = $source_category;
        $data[$key]['device_type'] = $lead_data->device_type;
        $data[$key]['post_id'] = $lead_data->post_id;
        $data[$key]['post_name'] = $post_name;

        $data[$key]['device_os'] = $lead_data->device_os;
        $data[$key]['form_type'] = $lead_data->form_type;
        $data[$key]['form_id'] = $lead_data->form_id;
        $data[$key]['source'] = $lead_data->source;
        $data[$key]['first_url_parameters'] = $lead_data->first_url_parameters;
        $data[$key]['device_browser_name'] = $lead_data->device_browser_name;
        $data[$key]['cost'] = $lead_data->cost;
        $data[$key]['form_name'] = $this->get_form_name($lead_data->form_id);
        $data[$key]['home_url'] = get_home_url();
        $data[$key]['device_os_version'] = $lead_data->device_os_version;
        $data[$key]['device_browser_version'] = $lead_data->device_browser_version;
        $data[$key]['device_height'] = $lead_data->device_height;
        $data[$key]['device_width'] = $lead_data->device_width;
        return $data;
    }

    public function get_leads_target_data($from, $to)
    {
        $forms = array_merge($this->functions->get_all_forms_cf7(), $this->functions->get_all_forms_wpforms(), $this->functions->get_all_forms_ninja());
        $leads_data = [];

        foreach ($forms as $key => $form) {
            $leads_data[$key]['title'] = $form['title'];
            $leads_data[$key]['type'] = $form['type'];
            $leads_data[$key]['count'] = $this->functions->get_count_leads_by_form_type($form['type'], $form['id'], $from, $to);
            $leads_data[$key]['sum'] = $this->functions->get_sum_leads_by_form_type($form['type'], $form['id'], $from, $to);
        }

        return isset($_GET['draw']) ? ['draw' => $_GET['draw'], 'recordsTotal' => 1, 'recordsFiltered' => 1, 'data' => $leads_data] : $leads_data;
    }

    public function get_leads_target_data_settings()
    {
        if (!isset($_GET['draw'])) {
            return [];
        }

        $result = $this->functions->scan_all_froms();

        return [
            "draw" => $_GET['draw'],
            "recordsTotal" => 1,
            "recordsFiltered" => 1,
            "data" => $result,
        ];
    }

    public function save_target_setting($type, $identifier, $cost, $status)
    {
        $this->functions->save_target_setting($type, $identifier, $cost, $status);
    }

}
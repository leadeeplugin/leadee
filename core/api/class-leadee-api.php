<?php

class LeadeeApi
{
    public function __construct()
    {
        $this->api = new LeadeeApiHelper();
        $this->functions = new LeadeeFunctions();
    }

    private function check_from_to()
    {
        if (!isset($_GET['from']) || !isset($_GET['to'])) {
            wp_send_json_error(array('message' => 'Invalid request'), 400);
        }
    }

    public function leadee_api()
    {
        if (!current_user_can('activate_plugins')) {
            wp_send_json_error(array('message' => 'Access Denied'), 403);
        }
        $from_num_format = 0;
        $to_num_format = 0;

        $gmt_offset = get_option('gmt_offset');
        $gmt_offset_sec =  $gmt_offset * 3600;

        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from_num_format = (int)$_GET['from'];
            $to_num_format = (int)$_GET['to'];
            $from = date('Y-m-d', intval($from_num_format / 1000) + $gmt_offset_sec) . " 00:00:00";
            $to = date('Y-m-d', intval($to_num_format / 1000) + $gmt_offset_sec) . " 23:59:59";
        }

        $timezone = timezone_name_from_abbr('', $gmt_offset_sec , true);

        if ($timezone === false) {
            $timezone = "UTC";
        }

        if (isset($_GET['timezone'])) {
            $timezone = $_GET['timezone'];
            ini_alter('date.timezone', $timezone);
            date_default_timezone_set($timezone);
        }

        switch ($_REQUEST['leadee-api']) {
            case 'leadee-data':
                $this->check_from_to();
                if (!isset($_GET['filter']) || !isset($_GET['timezone'])) {
                    wp_send_json_error(array('message' => 'Invalid request'), 400);
                }

                try {
                    $filter = $_GET['filter'];
                    $result = $this->api->get_leads_data($from, $to, $filter, $timezone);
                    wp_send_json($result);
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }
                break;
            case 'settings-set-option-value':
                if (isset($_POST['type']) && isset($_POST['option']) && isset($_POST['value'])) {
                    try {
                        $this->functions->set_setting_option_value($this->functions->clean($_POST['type']), $this->functions->clean($_POST['option']), (int)$_POST['value']);
                        wp_send_json_success(array('res' => 'saved'));
                    } catch (Exception $e) {
                        wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                    }
                } else {
                    wp_send_json_error(array('message' => 'Invalid request: Missing option or value'), 400);
                }
                break;
            case 'dashboard-get-leads-counter':
                try {
                    wp_send_json_success(array('allLeads' => $this->functions->get_total_leads()));
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }
                break;

            case 'dashboard-get-stat-data':
                $this->check_from_to();
                try {
                    $periodColor = $this->functions->get_period_data_from_calend($from_num_format, $to_num_format, $timezone);

                    $resultData = array(
                        "dataMainChart" => $this->functions->get_data_main_chart($periodColor),
                        "dataScreenSize" => $this->functions->get_data_screen_size($from, $to, 5),
                        "dataChartSource" => $this->functions->get_data_chart_source($periodColor),
                        "dataNewLeads" => $this->functions->get_data_new_leads($timezone, 6),
                        "countersData" => $this->functions->get_counters_data(),
                        "osClients" => $this->functions->get_os_clients_data_by_top($from, $to, 5),
                        "popularPages" => $this->functions->get_popular_pages_data($from, $to)
                    );

                    wp_send_json_success($resultData);
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }

                break;
            case 'leadee-data-target':
                $this->check_from_to();
                try {
                    $result = $this->api->get_leads_target_data($from, $to);
                    wp_send_json($result);
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }

                break;
            case 'save-target-setting':
                if (!isset($_POST['rows'])) {
                    wp_send_json_error(array('message' => 'Invalid request'), 400);
                }
                try {
                    $rows = $_POST['rows'];
                    foreach ($rows as $row) {
                        $type = $row['type'];
                        $identifier = $row['identifier'];
                        $cost = $row['cost'];
                        $status = $row['status'];
                        $this->api->save_target_setting($type, $identifier, $cost, $status);
                    }
                    wp_send_json_success(array('res' => 'saved'));
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }

                break;
            case 'leadee-data-goal-settings':
                try {
                    wp_send_json($this->api->get_leads_target_data_settings());
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }
                break;

            case 'get-last-lead-data':
                if (!isset($_GET['timezone'])) {
                    wp_send_json_error(array('message' => 'Invalid request'), 400);
                }
                $timezone = $_GET['timezone'];
                try {
                    wp_send_json_success($this->functions->get_last_lead_data($timezone));
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }
                break;

            case 'leadee-data-target-current':
                try {
                    wp_send_json_success($this->functions->read_current_month_targets_data());
                } catch (Exception $e) {
                    wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                }
                break;

            case 'target-month-sum-save':
                $sum = $this->functions->clean(trim($_POST['sum']));
                if (!empty($sum)) {
                    try {
                        $this->functions->set_setting_option_value('setting-target', 'month-target', (int)$sum);
                        wp_send_json_success(array('res' => 'saved'));
                    } catch (Exception $e) {
                        wp_send_json_error(array('message' => 'Error processing request: ' . $e->getMessage()), 500);
                    }


                } else {
                    wp_send_json_error(array('message' => 'Invalid request'), 400);
                }
                break;
            case 'export':
                $this->check_from_to();
                if (isset($_GET['type'])) {
                    $type = sanitize_text_field($_GET['type']);
                    $file = null;
                    switch ($type) {
                        case 'xls':
                            $pdf = new ExcelGenerator();
                            $file = $pdf->create_excel_doc($from, $to, $timezone);
                            break;
                        case 'csv':
                            $pdf = new CsvGenerator();
                            $file = $pdf->create_csv_doc($from, $to, $timezone);
                            break;
                        default:
                            wp_send_json_error(array('message' => 'Invalid request'), 400);
                            break;
                    }
                    wp_send_json_success(array('file' => base64_encode($file)));
                }
                break;
        }
    }
}

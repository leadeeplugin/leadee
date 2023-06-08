<?php

class WpFormsDriver implements FormDriver
{
    /**
     * @var LeadeeFunctions
     */
    private $functions;

    /**
     * @var Detector
     */
    private $detector;

    /**
     * @var wpdb
     */
    private $wpdb;

    /**
     * @var DriverHelper
     */
    private $driverHelper;

    public function __construct()
    {
        $this->functions = new LeadeeFunctions();
        $this->detector = new Detector();
        $this->driverHelper = new DriverHelper();

        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function run($data)
    {
        $fields = [];
        if (isset($data['wpforms']) && isset($data['wpforms']['fields'])) {
            foreach ($data['wpforms']['fields'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $sub_key => $sub_value) {
                        $fields[] = array("field" => "", "value" => $sub_value);
                    }
                } else {
                    $fields[] = array("field" => "", "value" => $value);
                }
            }
        }

        $data_arr = $this->detector->detect(
            isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            isset($_COOKIE['leadee_source']) ? $_COOKIE['leadee_source'] : '',
            isset($_COOKIE['leadee_first_visit_url']) ? $_COOKIE['leadee_first_visit_url'] : ''
        );

        //get post id
        $post_id = $this->driverHelper->take_page_post_id();
        $form_id = isset($data['wpforms']['id']) ? $data['wpforms']['id'] : '';
        $status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type('wpforms', $form_id);

        if (isset($status_and_cost_by_target_type->status) && $status_and_cost_by_target_type->status == 1 && !empty($form_id)) {
            $this->functions->write_lead($post_id, $form_id, $fields, $data_arr, 'wpforms', $status_and_cost_by_target_type->cost);
        }

        return 1;
    }
}

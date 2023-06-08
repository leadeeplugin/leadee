<?php

class NinjaDriver implements FormDriver
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
        //get post id
        $post_id = $this->driverHelper->take_page_post_id();

        $form_id = json_decode($_POST['formData'])->id;
        $fields = array();
        foreach (json_decode($_POST['formData'])->fields as $key => $obj) {
            $field = "";
            $fields[] = array('field' => $field, 'value' => $obj->value);
        }

        $leadee_source = '';
        $leadee_first_visit_url = '';
        if (isset($_COOKIE['leadee_source'])) {
            $leadee_source = $_COOKIE['leadee_source'];
        }
        if (isset($_COOKIE['leadee_first_visit_url'])) {
            $leadee_first_visit_url = $_COOKIE['leadee_first_visit_url'];
        }

        $data_arr = $this->detector->detect($_SERVER['HTTP_USER_AGENT'], $leadee_source, $leadee_first_visit_url);

        $status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type('ninja', $form_id);
        if (isset($status_and_cost_by_target_type->status) && $status_and_cost_by_target_type->status == 1) {
            $this->functions->write_lead($post_id, $form_id, $fields, $data_arr, 'ninja', $status_and_cost_by_target_type->cost);
        }
        return 1;
    }

}

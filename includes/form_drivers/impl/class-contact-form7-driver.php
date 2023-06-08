<?php

class ContactForm7Driver implements FormDriver
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
    }

    public function run($data)
    {
        $leadee_first_visit_url_param = 'leadee_first_visit_url';
        $leadee_source_param = 'leadee_source';
        //get post id
        $post_id = $this->driverHelper->take_page_post_id();

        $cookiesUtil = new CookiesUtil();
        $leadee_first_visit_url = $cookiesUtil->getCookie($leadee_first_visit_url_param) !== null ? sanitize_text_field($cookiesUtil->getCookie($leadee_first_visit_url_param)) : '';
        $leadee_source = $cookiesUtil->getCookie($leadee_source_param) !== null ? sanitize_text_field($cookiesUtil->getCookie($leadee_source_param)) : '';

        $data_arr = $this->detector->detect($_SERVER['HTTP_USER_AGENT'], $leadee_source, $leadee_first_visit_url);

        $fields = array_filter($data, function ($key) {
            return strpos($key, '_wpcf7') !== 0;
        }, ARRAY_FILTER_USE_KEY);

        $fields_for_db = [];
        $i = 0;
        foreach ($fields as $key => $item) {
            $fields_for_db[$i] = ['field' => "", 'value' => sanitize_text_field($item)];
            $i++;
        }

        $form_id = absint($data['_wpcf7']);
        $status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type('cf7', $form_id);

        if (isset($status_and_cost_by_target_type->status) && $status_and_cost_by_target_type->status == 1) {
            $this->functions->write_lead($post_id, $form_id, $fields_for_db, $data_arr, 'cf7', $status_and_cost_by_target_type->cost);
        }

        return 1;
    }
}
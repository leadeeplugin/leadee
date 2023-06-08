<?php

class LeadeeReceiver
{
    public function __construct()
    {
        $this->api = new LeadeeApiHelper();
        $this->functions = new LeadeeFunctions();
    }

    public function leadee_detect()
    {
        if (isset($_POST)) {
            $driver = FormDriverFactory::create();

            if (isset($driver)) {
                $driver->run($_POST);
            }
        }
    }
}

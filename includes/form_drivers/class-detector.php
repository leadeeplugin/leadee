<?php

class Detector
{

    public function __construct()
    {
        $this->functions = new LeadeeFunctions();
        $this->detector = new foroco\BrowserDetection();
    }

    /**
     * @param $useragent
     * @param $source
     * @param $leadee_first_visit_url
     * @return array
     */
    public function detect($useragent, $source, $leadee_first_visit_url)
    {
        $device_type = $this->detector->getDevice($useragent)['device_type'];
        $device_os = $this->detector->getOS($useragent)['os_name'];
        $device_os_version = $this->detector->getOS($useragent)['os_version'];
        $device_browser_name = $this->detector->getBrowser($useragent)['browser_name'];
        $device_browser_version = $this->detector->getBrowser($useragent)['browser_version'];

        $parameters = array();
        //get params
        $parsed_url = parse_url($leadee_first_visit_url);
        if (isset($parsed_url['query'])) {
            $parameters = $parsed_url['query'];
            $parameters = explode("&", $parameters);
        }

        if (isset($source)) {
            $domain = parse_url(html_entity_decode($source), PHP_URL_HOST);
        } else {
            $domain = $_SERVER['HTTP_HOST'];
        }

        $domain = $this->remove_www_if_exist($domain);

        $source_category = $this->functions->get_source_category($domain, $parameters);

        return array(
            'domain' => $domain,
            'source_category' => $source_category,
            'first_url_parameters' => json_encode($parameters),
            'device_type' => $device_type,
            'device_os' => $device_os,
            'device_os_version' => $device_os_version,
            'device_browser_name' => $device_browser_name,
            'device_browser_version' => $device_browser_version,
        );

    }

    /**
     * @param $domain
     * @return string|string[]|null
     */
    private function remove_www_if_exist($domain)
    {
        return preg_replace('/^www\./i', '', $domain);
    }
}
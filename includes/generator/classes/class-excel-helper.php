<?php

class ExcelHelper
{

    public function __construct()
    {
        $this->functions = new LeadeeFunctions();
    }

    /**
     * @param $exporter
     * @param $result
     */
    public function add_fiels_to_file($exporter, $result)
    {
        $columns = array("num" => "№", "dt" => "Date", "fields" => "Form fields", "source_category" => "Source category",
            "device_type" => "Device type", "post_name" => "Page", "device_os" => "Device OS", "form_type" => "Form type",
            "form_name" => "Form", "source" => "Source domain", "first_url_parameters" => "First visit parameters",
            "device_browser_name" => "Browser", "device_screen_size" => "Screen size");

        $columns = $this->remove_disable_columns($columns);

        $exporter->addRow(array_values($columns));

        if (isset($result) && isset($result['data']) && count($result['data']) > 0) {
            $i = 1;
            foreach ($result['data'] as $row) {
                $row["num"] = $i;
                $columnsKeys = array_keys($columns);
                $exporter->addRow($this->get_array_data_from_keys($row, $columnsKeys));
                $i++;
            }
        }
    }

    /**
     * @param $row
     * @param $keys
     * @return array
     */
    private function get_array_data_from_keys($row, $keys)
    {
        $result = [];

        $i = 0;
        foreach ($keys as $key) {
            switch ($key) {
                case "device_screen_size":
                    $result[$i] = $row["device_width"] . "x" . $row["device_height"];
                    break;
                case "device_os":
                    $result[$i] = $row["device_os"] . " " . $row["device_os_version"];
                    break;
                case "device_browser_name":
                    $result[$i] = $row["device_browser_name"] . " " . $row["device_browser_version"];
                    break;
                case "fields":
                    $result[$i] = $this->toLeadData($row["fields"]);
                    break;
                default:
                    $result[$i] = $row[$key];
            }
            $i++;
        }

        return $result;
    }

    /**
     * @param $columns
     * @return mixed
     */
    private function remove_disable_columns($columns)
    {
        $checkingColumns = array("dt", "source", "first_url_parameters", "device_screen_size");

        foreach ($checkingColumns as $key) {
            if (!$this->isColumnEnable($key)) {
                unset($columns[$key]);
            }
        }
        if (!$this->isColumnEnable("device_browser")) {
            unset($columns["device_browser_name"]);
        }
        return $columns;
    }

    /**
     * @param $option
     * @return bool
     */
    private function isColumnEnable($option)
    {
        return $this->functions->get_setting_option_value('leads-table-colums', $option) == 1;
    }

    /**
     * @param $fields
     * @return string
     */
    private function toLeadData($fields)
    {
        $res = "";
        foreach (json_decode($fields, true) as $field) {
            $res = $res . ((strlen($field["field_name"]) > 0) ? $field["field_name"] . ": " : "") . $field["value"] . " | ";
        }
        return $res;
    }
}
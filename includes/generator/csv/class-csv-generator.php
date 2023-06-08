<?php
class CsvGenerator
{

    public function __construct()
    {
        $this->api = new LeadeeApiHelper();
        $this->excelhelper = new ExcelHelper();
    }

    public function create_csv_doc($from, $to, $timezone)
    {
        $filename = 'leadee-' . $from . '_' . $to . '.csv';
        $exporter = new ExportDataCSV('string', $filename);
        $result = $this->api->get_leads_data($from, $to, '', $timezone);
        $exporter->initialize();
        $this->excelhelper->add_fiels_to_file($exporter, $result);
        $exporter->finalize();
        return $exporter->getString();
    }
}
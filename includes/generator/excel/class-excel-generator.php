<?php
class ExcelGenerator
{

    public function __construct()
    {
        $this->api = new LeadeeApiHelper();
        $this->excelhelper = new ExcelHelper();
    }

    public function create_excel_doc($from, $to, $timezone)
    {
        $filename = 'leadee-' . $from . '_' . $to . '.xls';
        $exporter = new ExportDataExcel('string', $filename);
        $result = $this->api->get_leads_data($from, $to, '', $timezone);
        $exporter->initialize();
        $this->excelhelper->add_fiels_to_file($exporter, $result);
        $exporter->finalize();
        return $exporter->getString();
    }
}

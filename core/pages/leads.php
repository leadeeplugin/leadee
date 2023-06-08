<?php
//leads template
$scriptsLoader = new ScriptsLoader();
$scriptsLoader->load_scripts_page_leads();
$functions = new LeadeeFunctions();
?>
<section>
    <div class="card" id="leads-stat">
        <div class="card-content">
            <div class="main-chart">
                <h2 class="title-medium"><?php _e('Leads statistics',LEADEE_PLUGIN_NAME); ?></h2>
                <div class="title-detail"><?php _e('Average count of leads',LEADEE_PLUGIN_NAME); ?></div>
                <canvas id="main-chart" width="400" height="400" class=""></canvas>
            </div>
        </div>
    </div>
    
    <div class="card table-leads" id="table-leads">
        <div id="leads-filter-block"></div>
        <div class="row">
            <div class="col-100">
                <table id="leads-list" class="table leads-table">
                    <thead>
                    <tr>
                        <?php if ($functions->get_setting_option_value('leads-table-colums', 'dt') == 1) { ?>
                            <th><?php _e('Date',LEADEE_PLUGIN_NAME); ?></th><?php } ?>
                        <th><?php _e('Form fields',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Source type',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Device type',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Page',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Device OS',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Form type',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Form',LEADEE_PLUGIN_NAME); ?></th>
                        <?php if ($functions->isEnableColumn('source')) { ?>
                            <th><?php _e('Source',LEADEE_PLUGIN_NAME); ?></th><?php } ?>
                        <?php if ($functions->isEnableColumn('first_url_parameters')) { ?>
                            <th><?php _e('First visit params',LEADEE_PLUGIN_NAME); ?></th><?php } ?>
                        <?php if ($functions->isEnableColumn('device_browser')) { ?>
                            <th><?php _e('Browser',LEADEE_PLUGIN_NAME); ?></th><?php } ?>
                        <?php if ($functions->isEnableColumn('device_screen_size')) { ?>
                            <th><?php _e('Screen',LEADEE_PLUGIN_NAME); ?></th><?php } ?>
                        <th><?php _e('Cost',LEADEE_PLUGIN_NAME); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>

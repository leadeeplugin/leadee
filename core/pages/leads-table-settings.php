<?php
//dashboard template
$scriptsLoader = new ScriptsLoader();
$scriptsLoader->load_scripts_page_leads_table_settings();
$functions = new LeadeeFunctions();
?>
<section>
    <div class="card leads-table-setting" id="leads-table-setting">

        <div>
            <div class="co(ntent-title">
                <h2 class="title-medium"><?php _e('Column settings',LEADEE_PLUGIN_NAME); ?></h2>
                <div class="title-detail"><?php _e('For leads',LEADEE_PLUGIN_NAME); ?></div>
            </div>
        </div>

        <!-- page content -->
        <div class="right_col" role="main" id="leads-table-setting-for-tour">

            <table class="table table-striped table-bordered dataTable no-footer dtr-inline setting-columns"
                   style="width: 100%;  margin-top: 60px !important;" role="grid" aria-describedby="datatable-buttons_info">
                <thead>
                <tr role="row">
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 153px;"
                        aria-label="Date: activate to sort column ascending">
                        <div class="checkbox data-block">

                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox"
                                       data-type="dt"
                                       class="js-switch" <?php echo ($functions->get_setting_option_value('leads-table-colums', 'dt') == 1) ? 'checked' : '' ?>>
                                <span class="toggle-icon"></span>
                                <p><?php _e('Date',LEADEE_PLUGIN_NAME); ?></p>
                            </label>

                        </div>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 72px;"
                        aria-label="Form fields: activate to sort column ascending"><?php _e('Form fields',LEADEE_PLUGIN_NAME); ?>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 55px;"
                        aria-label="Source category: activate to sort column ascending"><?php _e('Source type',LEADEE_PLUGIN_NAME); ?>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 43px;"
                        aria-label="Device type: activate to sort column ascending"><?php _e('Device type',LEADEE_PLUGIN_NAME); ?>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 38px;"
                        aria-label="Page: activate to sort column ascending"><?php _e('Page',LEADEE_PLUGIN_NAME); ?>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 43px;" aria-label="Device OS: activate to sort column ascending">
                        <p><?php _e('Device OS',LEADEE_PLUGIN_NAME); ?></p>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 50px;" aria-label="Form type: activate to sort column ascending">
                        <?php _e('Form type',LEADEE_PLUGIN_NAME); ?>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 33px;" aria-label="orm: activate to sort column ascending">
                        <p><?php _e('Form',LEADEE_PLUGIN_NAME); ?></p>
                    </th>

                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 74px;"
                        aria-label="Source domain: activate to sort column ascending">
                        <div class="checkbox data-block">
                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox"
                                       data-type="source"
                                       class="js-switch" <?php echo ($functions->get_setting_option_value('leads-table-colums', 'source') == 1) ? 'checked' : '' ?>>
                                <span class="toggle-icon"></span>
                                <p><?php _e('Source',LEADEE_PLUGIN_NAME); ?></p>
                            </label>
                        </div>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 200px;"
                        aria-label="First visit parameters: activate to sort column ascending">
                        <div class="checkbox data-block">

                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox"
                                       data-type="first_url_parameters"
                                       class="js-switch" <?php echo ($functions->get_setting_option_value('leads-table-colums', 'first_url_parameters') == 1) ? 'checked' : '' ?>>
                                <span class="toggle-icon"></span>
                                <p style="width: 200px;"><?php _e('First visit params',LEADEE_PLUGIN_NAME); ?></p>
                            </label>
                        </div>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 52px;" aria-label="Browser: activate to sort column ascending">
                        <div class="checkbox data-block">
                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox"
                                       data-type="device_browser"
                                       class="js-switch" <?php echo ($functions->get_setting_option_value('leads-table-colums', 'device_browser') == 1) ? 'checked' : '' ?>>
                                <span class="toggle-icon"></span>
                                <p><?php _e('Browser',LEADEE_PLUGIN_NAME); ?></p>
                            </label>
                        </div>
                    </th>
                    <th tabindex="0" aria-controls="datatable-buttons" rowspan="1"
                        colspan="1"
                        style="width: 47px;"
                        aria-label="Screen size: activate to sort column ascending">
                        <div class="checkbox data-block">
                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox"
                                       data-type="device_screen_size"
                                       class="js-switch" <?php echo ($functions->get_setting_option_value('leads-table-colums', 'device_screen_size') == 1) ? 'checked' : '' ?>>
                                <span class="toggle-icon"></span>
                                <p><?php _e('Screen',LEADEE_PLUGIN_NAME); ?></p>
                            </label>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr role="row" class="odd">
                    <td>2021-04-21 19:42:28</td>
                    <td>
                        <span><b></b> John Smith </span><br><span><b></b> johnsmith@leadee.io</span><br><span><b></b> Want product</span><br><span><b></b> I want this company product, call me back</span><br>
                    </td>
                    <td>Referal</td>
                    <td>mobile</td>
                    <td><a href="#" target="_blank">Contact
                            7 Page</a></td>
                    <td>iOS 14.4</td>
                    <td>cf7</td>
                    <td>
                        <a href="#"
                           target="_blank">Contact form 1</a></td>
                    <td>website.com</td>
                    <td>
                        <span>utm_source=myads</span><br><span>utm_medium=cpc</span><br><span>utm_campaign=s_company</span><br><span>utm_content={ad_id}</span><br><span>utm_term={keyword}</span><br>
                    </td>
                    <td>Safari Mobile 14</td>
                    <td>414x736</td>
                </tr>

                <tr role="row" class="odd">
                    <td>2021-04-21 21:43:21</td>
                    <td>
                        liza@leadee.io</span><br><span><b></b> Want product 2</span><br><span><b></b> I want this company product, call me back</span><br>
                    </td>
                    <td>Referal</td>
                    <td>mobile</td>
                    <td><a href="#" target="_blank">Contact
                            7 Page</a></td>
                    <td>iOS 14.4</td>
                    <td>cf7</td>
                    <td>
                        <a href="#" target="_blank">Contact form 1</a></td>
                    <td>website.com</td>
                    <td>
                        <span>utm_medium=cpc</span><br><span>utm_campaign=s_company</span><br><span>utm_content={ad_id}</span><br><span>utm_term={keyword}</span><br>
                    </td>
                    <td>Safari Mobile 14</td>
                    <td>414x736</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
$scriptsLoader = new ScriptsLoader();
$scriptsLoader->load_scripts_page_goals_settings();
$functions = new LeadeeFunctions();
?>
<section>
    <div class="row">
        <div class="col-100 medium-70">
            <div class="card" id="form-set-goal-sum">
                <div class="settings-save-block">
                    <div class="row">
                        <div>
                            <div class="content-title">
                                <h2 class="title-medium"><?php _e('Goal sum setting for forms',LEADEE_PLUGIN_NAME); ?></h2>
                                <div class="title-detail"><?php _e('The table reflects the cost of the goals that will come from contact forms',LEADEE_PLUGIN_NAME); ?></div>
                            </div>
                        </div>
                        <div>
                            <button class="button button-raised button-fill button-round big-button button-green"
                                    id="target-settings-save-button"><i
                                        class="leadee-icon icon-save-data-light"></i><span
                                        class=""><?php _e('Save data',LEADEE_PLUGIN_NAME); ?></span>
                            </button>
                        </div>
                    </div>

                    <div class="card data-table goals-setting-table">

                        <table id="leads-list-target-settings" class="dark-table">
                            <thead>
                            <tr>
                                <th class="numeric-cell"><?php _e('Title',LEADEE_PLUGIN_NAME); ?></th>
                                <th class="numeric-cell"><?php _e('Type',LEADEE_PLUGIN_NAME); ?></th>
                                <th class="numeric-cell"><?php _e('Status',LEADEE_PLUGIN_NAME); ?></th>
                                <th class="numeric-cell"><?php _e('Sum',LEADEE_PLUGIN_NAME); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-100 medium-30">
            <div class="card" id="goal-setting">
                <div class="target-chart">
                    <?php require_once LEADEE_PLUGIN_DIR . '/core/pages/components/smile-bar.php'; ?>
                </div>
                <div class="content-title">
                    <h2 class="title-medium"><?php _e('Enter goal amount',LEADEE_PLUGIN_NAME); ?></h2>
                    <div class="target-medium-info">
                        <?php _e("How much do you want leads and conversions that which users will  for <strong>1 month</strong>?",LEADEE_PLUGIN_NAME); ?>
                    </div>

                    <div class="item-target-form">
                        <div class="row">
                            <div class="item-input-wrap vert-center area-input">
                                <input type="number" id="target-month-sum"
                                       placeholder="<?php _e('Enter amount in $',LEADEE_PLUGIN_NAME); ?>" class="">
                            </div>
                                    <div>
                                        <button class="button button-raised button-fill button-round big-button button-green"
                                                id="target-month-sum-save-button"><i
                                                    class="leadee-icon icon-save-data-light"></i><span
                                                    class=""><?php _e('Save data',LEADEE_PLUGIN_NAME); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="target-small-info">
                                <div class="target-small-child">
                                    <i class="leadee-icon icon-info"></i>
                                    <span><?php _e('Goal = useful action on the site (enter message in form, etc.).',LEADEE_PLUGIN_NAME); ?></span>
                                </div>
                                <div class="target-small-child">
                                    <i class="leadee-icon icon-info"></i>
                                    <span><?php _e('All goals are summarized in quantitative and monetary terms.',LEADEE_PLUGIN_NAME); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

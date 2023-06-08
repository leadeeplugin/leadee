<?php
//goals template
$scriptsLoader = new ScriptsLoader();
$scriptsLoader->load_scripts_page_goals();
$functions = new LeadeeFunctions();
?>

<section>
    <div class="row section-row">
        <div class="col-70">
            <div class="card" id="leads-stat">
                <div class="card-content">
                    <div class="main-chart">
                        <h2 class="title-medium"><?php _e('Goals Statistics',LEADEE_PLUGIN_NAME); ?></h2>
                        <div class="title-detail"><?php _e('Average count of goals',LEADEE_PLUGIN_NAME); ?></div>
                        <canvas id="main-chart" width="400" height="400" class=""></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-30">
            <div class="card" id="goals-status">
                <div class="target-chart">
                    <?php require_once LEADEE_PLUGIN_DIR . '/core/pages/components/smile-bar.php'; ?>
                </div>
                <div class="content-title">
                    <h2 class="title-medium"><?php _e('Conversion goals',LEADEE_PLUGIN_NAME); ?></h2>
                    <div class="target-medium-info"><?php _e('How close to the target <strong>for the current month</strong>',LEADEE_PLUGIN_NAME); ?></div>
                </div>
                <div class="target-small-info">
                    <div class="target-small-child">
                        <i class="leadee-icon icon-target-green"></i>
                        <span class=""><?php _e('Your goal:',LEADEE_PLUGIN_NAME); ?><span id="month-target">0</span></span>
                    </div>
                    <div class="target-small-child">
                        <i class="leadee-icon icon-info"></i>
                        <span class=""><?php _e('Goal = useful action on the site (enter message in form, etc.).',LEADEE_PLUGIN_NAME); ?></span>
                    </div>
                    <div class="target-small-child">
                        <i class="leadee-icon icon-info"></i>
                        <span><?php _e('All goals are summarized in quantitative and monetary terms.',LEADEE_PLUGIN_NAME); ?></span>
                    </div>
                    <a href="<?php echo get_site_url(); ?>/?leadee-page=goals-settings" id="goals-button"
                       class="button button-raised button-fill button-round big-button button-green">
                        <i class="leadee-icon icon-right-white"></i> <span
                                class=""><?php _e('Set goal',LEADEE_PLUGIN_NAME); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-leads" id="table-leads-target">
        <div class="row">
            <div class="col-100">
                <table id="leads-list-target" class="table leads-table">
                    <thead>
                    <tr>
                        <th><?php _e('Title',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Type',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Count',LEADEE_PLUGIN_NAME); ?></th>
                        <th><?php _e('Sum',LEADEE_PLUGIN_NAME); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

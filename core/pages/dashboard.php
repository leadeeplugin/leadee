<?php
//dashboard template
$scriptsLoader = new ScriptsLoader();
$scriptsLoader->load_scripts_page_dashboard();
?>
<section>
    <div class="row section-row">
        <div class="col-100 medium-70">
            <div class="card" id="leads-stat">
                <div class="card-content">
                    <div class="main-chart">
                        <h2 class="title-medium"><?php _e('Leads statistics',LEADEE_PLUGIN_NAME); ?></h2>
                        <div class="title-detail"><?php _e('Average count of leads',LEADEE_PLUGIN_NAME); ?></div>
                        <canvas id="main-chart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-100 medium-30">
            <div class="card" id="new-leads">
                <div class="leads-container">
                    <div>
                        <img class="header-leads"
                             src="<?php echo LEADEE_PLUGIN_URL . '/core/assets'; ?>/image/header-leads.png"
                             alt="">
                    </div>
                    <div class="list">
                        <h2 class="title-medium"><?php _e('Last leads',LEADEE_PLUGIN_NAME); ?></h2>
                        <ul class="leads-new-widget" id="leads-new-widget">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="target-swiper">
        <div class="row section-row" id="leads-sources-block">
            <div class="col-100 medium-40">
                <div class="card" id="target-plan">
                    <div class="target-plan">
                        <h2 class="title-medium"><?php _e('Goals of leads',LEADEE_PLUGIN_NAME); ?></h2>
                        <small>% <?php _e('of goal achievement per current month',LEADEE_PLUGIN_NAME); ?></small>
                        <div class="row">
                            <div class="col-100 medium-40 ord2-desktop">
                                <div class="gauge target-gauge"></div>
                            </div>
                            <div class="col-100 medium-60 ord1-desktop">
                                <div class="card-content" id="target-block">
                                    <div class="s" id="target-user"><?php _e('Goal amount',LEADEE_PLUGIN_NAME); ?>: <b>0</b>
                                    </div>
                                    <div class="s" id="target-current"><?php _e('Leads month sum',LEADEE_PLUGIN_NAME); ?>: <b>0</b>
                                    </div>
                                    <div class="s hidden"
                                         id="target-none"><?php _e('Goal not set!',LEADEE_PLUGIN_NAME); ?></div>
                                    <a href="<?php echo get_site_url(); ?>/?leadee-page=goals-settings"
                                       class="button  button-fill button-target button-green-style hidden"
                                       id="target-button">
                                        <i
                                                class="leadee-icon icon-right-white"></i>
                                        <span><?php _e('Set goal',LEADEE_PLUGIN_NAME); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-100 medium-60">
                <div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10"
                     data-slides-per-view="auto" data-centered-slides="true"
                     class="swiper-container swiper-init swiper-mult demo-swiper-multiple-auto">
                    <div class="swiper-pagination"></div>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide swiper-40 swiper-md-40">
                            <div class="card" id="counter-1">

                                <div class="card-content card-content-padding">
                                    <h2 class="title-medium"><?php _e('Today',LEADEE_PLUGIN_NAME); ?></h2>
                                    <div class="block-counter" id="counter-today">
                                        <span><span>0</span><sup></sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide swiper-40 swiper-md-40">
                            <div class="card" id="counter-2">
                                <div class="card-content card-content-padding ">
                                    <h2 class="title-medium"><?php _e('Yesterday',LEADEE_PLUGIN_NAME); ?></h2>
                                    <div class="block-counter" id="counter-yesterday">
                                        <span><span>0</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide swiper-40 swiper-md-40">
                            <div class="card" id="counter-3">
                                <div class="card-content card-content-padding">
                                    <h2 class="title-medium"><?php _e('Week',LEADEE_PLUGIN_NAME); ?></h2>
                                    <div class="block-counter" id="counter-week">
                                        <span><span>0</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="card">
        <div class="row section-row">
            <div class="col-100 medium-20 ord2-mobile col-md-100">
                <div id="source">
                    <div class="card-content card-content-padding">
                        <h2 class="title-medium"><?php _e('Leads sources',LEADEE_PLUGIN_NAME); ?></h2>
                        <div class=""><?php _e('Where do leads come from',LEADEE_PLUGIN_NAME); ?></div>
                        <div class="leads-from-block">
                            <ul>
                                <li class="row">
                                    <div class="col-10">
                                        <i class="icon-dot icon-dot-dark-blue"></i>
                                    </div>
                                    <div class="col-70 medium-80">
                                        <span><?php _e('Search engines',LEADEE_PLUGIN_NAME); ?></span>
                                        <small><?php _e('Google, Bing, Yandex, etc',LEADEE_PLUGIN_NAME); ?></small>
                                    </div>
                                    <div class="col-10 medium-40 hidden-lg">
                                        <div class="progressbar-block">
                                            <div class="amount-block">
                                                <span class="el1">0</span>
                                                <span class="el2">0%</span></div>
                                            <div class="progressbar color-dark-blue" data-progress="10">
                                                <span style="transform: translate3d(-80%, 0px, 0px);"></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="row">
                                    <div class="col-10">
                                        <i class="icon-dot icon-dot-green"></i>
                                    </div>
                                    <div class="col-70 medium-80">
                                        <span><?php _e('Advertising systems',LEADEE_PLUGIN_NAME); ?></span>
                                        <small><?php _e('Google Ads, FB Ads, etc',LEADEE_PLUGIN_NAME); ?></small>
                                    </div>

                                    <div class="col-10 medium-40 hidden-lg">
                                        <div class="progressbar-block">
                                            <div class="amount-block">
                                                <span class="el1">0</span>
                                                <span class="el2">0%</span></div>
                                            <div class="progressbar color-green" data-progress="10">
                                                <span style="transform: translate3d(-70%, 0px, 0px);"></span>
                                            </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="row">
                                            <div class="col-10">
                                                <i class="icon-dot icon-dot-yellow"></i>
                                            </div>
                                            <div class="col-70 medium-80">
                                                <span><?php _e('Social networks',LEADEE_PLUGIN_NAME); ?></span>
                                                <small><?php _e('Facebook, Instagram, VK, etc',LEADEE_PLUGIN_NAME); ?></small>
                                            </div>
                                            <div class="col-10 medium-40  hidden-lg">
                                                <div class="progressbar-block">
                                                    <div class="amount-block">
                                                        <span class="el1">0</span>
                                                        <span class="el2">0%</span></div>
                                                    <div class="progressbar color-yellow" data-progress="10">
                                                        <span style="transform: translate3d(-30%, 0px, 0px);"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="row">
                                            <div class="col-10">
                                                <i class="icon-dot icon-dot-red"></i>
                                            </div>
                                            <div class="col-70 medium-80">
                                                <span><?php _e('Referral traffic',LEADEE_PLUGIN_NAME); ?></span>
                                                <small><?php _e('Websites, forums, blogs, etc.',LEADEE_PLUGIN_NAME); ?></small>
                                            </div>

                                            <div class="col-10 medium-40  hidden-lg">
                                                <div class="progressbar-block">
                                                    <div class="amount-block">
                                                        <span class="el1">0</span>
                                                        <span class="el2">0%</span></div>
                                                    <div class="progressbar color-red" data-progress="10">
                                                        <span style="transform: translate3d(-50%, 0px, 0px);"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="row">
                                            <div class="col-10">
                                                <i class="icon-dot icon-dot-gray"></i>
                                            </div>
                                            <div class="col-70 medium-80">
                                                <span><?php _e('Direct traffic',LEADEE_PLUGIN_NAME); ?></span>
                                                <small><?php _e('From those who entered the site address',LEADEE_PLUGIN_NAME); ?></small>
                                            </div>

                                            <div class="col-10 medium-40  hidden-lg">
                                                <div class="progressbar-block">
                                                    <div class="amount-block">
                                                        <span class="el1">0</span>
                                                        <span class="el2">0%</span></div>
                                                    <div class="progressbar color-gray" data-progress="10">
                                                        <span style="transform: translate3d(-10%, 0px, 0px);"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-100 medium-80 ord1-mobile col-md-100 hidden-mobile">
                        <div id="source-chart">
                            <div class="card-content card-content-padding">
                                <div class="uk-card uk-card-default uk-card-body source-chart">
                                    <canvas class="chart" id="sourceChart" width="400"
                                            height="400"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10"
                 data-slides-per-view="auto" data-centered-slides="true"
                 class="swiper-container swiper-init swiper-mult demo-swiper-multiple-auto bottom-slider">
                <div class="swiper-pagination"></div>
                <div class="swiper-wrapper section-row"  id="popular-screen-sizes">
                    <div class="swiper-slide swiper-80 swiper-md-80 swiper-lg-40">
                        <div class="card" id="popular-screen">
                            <div class="card-content card-content-padding">
                                <h2 class="title-medium"><?php _e('Popular screen sizes',LEADEE_PLUGIN_NAME); ?></h2>
                                <div><?php _e('With these screen sizes, requests are left most often. Top 5',LEADEE_PLUGIN_NAME); ?></div>
                                <canvas class="chart" id="chartScreenSize" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide swiper-80 swiper-md-40 swiper-lg-20">
                        <div class="card" id="popular-os">
                            <div class="card-content card-content-padding">
                                <h2 class="title-medium"><?php _e('OS clients',LEADEE_PLUGIN_NAME); ?></h2>
                                <div><?php _e('Operating systems. Top 5',LEADEE_PLUGIN_NAME); ?></div>
                                <ul class="os-clients" id="os-clients">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide swiper-80 swiper-md-40 swiper-lg-40" >
                        <div class="card" id="popular-pages">
                            <div class="card-content card-content-padding popular-widget ">
                                <h2 class="title-medium"><?php _e('Popular Pages',LEADEE_PLUGIN_NAME); ?></h2>
                                <div class=""><?php _e('This is popular pages of clients leads. Top 5',LEADEE_PLUGIN_NAME); ?></div>
                                <div class="leads-from-block">
                                    <ul id="popular-pages-data">
                                        <li class="row">
                                            <div class="col-10">
                                            </div>
                                            <div class="col-50">
                                                <a class="button button-fill button-target button-green-style"><i
                                                            class="leadee-icon icon-right-black"></i> <?php _e('View all',LEADEE_PLUGIN_NAME); ?>
                                                </a>
                                            </div>
                                            <div class="col-40">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

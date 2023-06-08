<?php
global $current_user;
wp_get_current_user();
$avatar_url = get_avatar_url($GLOBALS['current_user'], array(
    'size' => 48,
    'default' => 'wavatar',
));

?>
<style>
    .leadee-preloader-area{
        background: #E5E5E5;
        overflow: hidden;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 9999999999999;
    }
    .leadee-preloader{
        position: absolute;
        height: 233px;
        background-size: 40px;
        z-index: 9999999999999;
        left: 50%;
        top: 50%;
        margin: -125px 0 0 -125px;
    }
</style>
<body class="body-light">
<div id="app">
    <div class="top-navbar navbar navbar-large navbar-transparent navbar-large-collapsed">
        <div class="navbar-bg" style=""></div>
        <div class="navbar-inner navbar-inner-centered-title">
            <div class="left"><a href="#" class="link icon-only panel-open" data-panel=".panel-left"><i
                            class="leadee-icon icon-hamburger"></i></a></div>
            <div class="header-content">
                <div id="header-calend" class="calend">
                </div>
                <a class="donate-top-mobile" href="https://donate.leadee.io" target="_blank"> <i
                            class="leadee-icon leadee-icon-big icon-donate"></i>
                    <span><?php _e('Donate us', LEADEE_PLUGIN_NAME); ?></span> </a>
            </div>
            <div class="header-desktop-content">
                <div class="h-left">
                    <div class="desc-logo">
                        <div class="sidebar-logo">
                            <svg width="100%" height="50" viewBox="0 0 235 40"  fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2556_8499)">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.96768 7.234C1.77639 7.234 0 9.0047 0 11.1889V21.1761C0 26.5408 3.07545 31.4004 7.93536 33.7083V11.1889C7.93536 9.00465 6.15898 7.234 3.96768 7.234Z" fill="#FF6E42"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.54 12.5921C14.3486 12.5921 12.5723 14.3628 12.5723 16.547V33.6908C15.2181 33.6908 17.8618 33.6908 20.5076 33.6908V16.547C20.5076 14.3628 18.7313 12.5921 16.54 12.5921Z" fill="#FCC02A"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M29.1151 1.20833C26.9244 1.20833 25.1484 2.9785 25.1484 5.16212V33.6908C29.5298 33.6908 33.0816 30.1504 33.0816 25.7832V5.16212C33.0816 2.9785 31.3057 1.20833 29.1151 1.20833Z" fill="#8AC44B"/>
                                    <path d="M49.9593 33.509C47.3549 33.509 45.3229 32.8243 43.8633 31.455C42.4036 30.0571 41.6738 28.0887 41.6738 25.5497V1.41525H48.3709V25.1646C48.3709 26.1346 48.6142 26.8905 49.1007 27.4326C49.6159 27.9461 50.3314 28.2028 51.2472 28.2028C51.5906 28.2028 51.9341 28.16 52.2775 28.0745C52.6496 27.9889 52.9358 27.889 53.1361 27.7749L53.4366 32.9527C52.3205 33.3236 51.1613 33.509 49.9593 33.509ZM67.9394 28.16C69.1414 28.16 70.2 27.9889 71.1162 27.6465C72.0606 27.2757 72.9334 26.7051 73.7349 25.9349L77.2981 29.7861C75.1228 32.268 71.946 33.509 67.7676 33.509C65.163 33.509 62.859 33.0097 60.8559 32.0113C58.8523 30.9843 57.3069 29.5722 56.2195 27.7749C55.1316 25.9777 54.5881 23.9379 54.5881 21.6557C54.5881 19.402 55.1174 17.3765 56.1765 15.5793C57.2639 13.7535 58.7377 12.3414 60.5983 11.3429C62.4873 10.3159 64.605 9.80242 66.952 9.80242C69.1555 9.80242 71.1591 10.2731 72.9622 11.2145C74.7652 12.1274 76.196 13.4683 77.2552 15.237C78.3425 16.9772 78.8865 19.0454 78.8865 21.4417L61.8004 24.7367C62.2867 25.8778 63.0453 26.7337 64.0757 27.3042C65.1342 27.8747 66.4221 28.16 67.9394 28.16ZM66.952 14.8518C65.263 14.8518 63.8893 15.3939 62.8307 16.4779C61.7716 17.562 61.2135 19.0597 61.1564 20.971L72.4041 18.7887C72.0889 17.5905 71.4449 16.6348 70.4722 15.9216C69.499 15.2084 68.3257 14.8518 66.952 14.8518ZM106.828 10.1447V33.1667H100.431V30.5136C98.7712 32.5105 96.3672 33.509 93.2191 33.509C91.0438 33.509 89.0691 33.024 87.2948 32.0541C85.5488 31.0841 84.175 29.7005 83.1735 27.9033C82.1715 26.106 81.671 24.0235 81.671 21.6557C81.671 19.2879 82.1715 17.2054 83.1735 15.4081C84.175 13.6109 85.5488 12.2273 87.2948 11.2573C89.0691 10.2874 91.0438 9.80242 93.2191 9.80242C96.1666 9.80242 98.4707 10.7296 100.131 12.5839V10.1447H106.828ZM94.3782 28.0317C96.0666 28.0317 97.4692 27.4611 98.5854 26.32C99.7016 25.1504 100.26 23.5956 100.26 21.6557C100.26 19.7158 99.7016 18.1753 98.5854 17.0342C97.4692 15.8646 96.0666 15.2797 94.3782 15.2797C92.661 15.2797 91.2444 15.8646 90.1282 17.0342C89.012 18.1753 88.4539 19.7158 88.4539 21.6557C88.4539 23.5956 89.012 25.1504 90.1282 26.32C91.2444 27.4611 92.661 28.0317 94.3782 28.0317ZM136.554 1.41525V33.1667H130.157V30.5136C128.497 32.5105 126.093 33.509 122.945 33.509C120.77 33.509 118.795 33.024 117.021 32.0541C115.275 31.0841 113.901 29.7005 112.9 27.9033C111.897 26.106 111.397 24.0235 111.397 21.6557C111.397 19.2879 111.897 17.2054 112.9 15.4081C113.901 13.6109 115.275 12.2273 117.021 11.2573C118.795 10.2874 120.77 9.80242 122.945 9.80242C125.893 9.80242 128.197 10.7296 129.857 12.5839V1.41525H136.554ZM124.104 28.0317C125.793 28.0317 127.195 27.4611 128.311 26.32C129.428 25.1504 129.986 23.5956 129.986 21.6557C129.986 19.7158 129.428 18.1753 128.311 17.0342C127.195 15.8646 125.793 15.2797 124.104 15.2797C122.387 15.2797 120.97 15.8646 119.854 17.0342C118.738 18.1753 118.18 19.7158 118.18 21.6557C118.18 23.5956 118.738 25.1504 119.854 26.32C120.97 27.4611 122.387 28.0317 124.104 28.0317Z" fill="#343A40"/>
                                    <path d="M154.386 28.16C155.588 28.16 156.648 27.9889 157.563 27.6465C158.508 27.2757 159.38 26.7051 160.182 25.9349L163.745 29.7861C161.57 32.268 158.393 33.509 154.215 33.509C151.61 33.509 149.307 33.0097 147.303 32.0113C145.299 30.9843 143.754 29.5722 142.667 27.7749C141.579 25.9777 141.035 23.9379 141.035 21.6557C141.035 19.402 141.564 17.3765 142.624 15.5793C143.711 13.7535 145.185 12.3414 147.045 11.3429C148.934 10.3159 151.052 9.80242 153.399 9.80242C155.603 9.80242 157.606 10.2731 159.409 11.2145C161.212 12.1274 162.643 13.4683 163.702 15.237C164.79 16.9772 165.334 19.0454 165.334 21.4418L148.247 24.7367C148.734 25.8778 149.492 26.7337 150.523 27.3042C151.582 27.8747 152.87 28.16 154.386 28.16ZM153.399 14.8518C151.711 14.8518 150.337 15.3939 149.278 16.4779C148.219 17.562 147.661 19.0597 147.603 20.971L158.851 18.7887C158.536 17.5905 157.893 16.6348 156.919 15.9216C155.946 15.2084 154.773 14.8518 153.399 14.8518ZM181.469 28.16C182.671 28.16 183.73 27.9889 184.646 27.6465C185.591 27.2757 186.463 26.7051 187.265 25.9349L190.828 29.7861C188.653 32.268 185.476 33.509 181.297 33.509C178.693 33.509 176.389 33.0097 174.386 32.0113C172.382 30.9843 170.837 29.5722 169.749 27.7749C168.661 25.9777 168.118 23.9379 168.118 21.6557C168.118 19.402 168.647 17.3765 169.706 15.5793C170.794 13.7535 172.268 12.3414 174.128 11.3429C176.017 10.3159 178.135 9.80242 180.482 9.80242C182.685 9.80242 184.689 10.2731 186.492 11.2145C188.295 12.1274 189.726 13.4683 190.785 15.237C191.872 16.9772 192.416 19.0454 192.416 21.4418L175.33 24.7367C175.817 25.8778 176.575 26.7337 177.606 27.3042C178.664 27.8747 179.952 28.16 181.469 28.16ZM180.482 14.8518C178.793 14.8518 177.419 15.3939 176.361 16.4779C175.301 17.562 174.743 19.0597 174.686 20.971L185.934 18.7887C185.619 17.5905 184.975 16.6348 184.002 15.9216C183.029 15.2084 181.856 14.8518 180.482 14.8518Z" fill="#343A40"/>
                                    <path d="M171.717 3.37825C172.22 3.37825 172.674 3.49487 173.08 3.72817C173.486 3.95458 173.802 4.27704 174.03 4.69558C174.264 5.10725 174.381 5.57725 174.381 6.10554C174.381 6.63383 174.264 7.10383 174.03 7.5155C173.802 7.92717 173.486 8.24962 173.08 8.48292C172.674 8.71621 172.22 8.83283 171.717 8.83283C171.235 8.83283 170.798 8.71963 170.406 8.49321C170.02 8.25991 169.721 7.93746 169.507 7.52579V8.79167H169.012V1.15525H169.528V4.64413C169.742 4.24616 170.041 3.93741 170.426 3.71788C170.812 3.49146 171.242 3.37825 171.717 3.37825ZM171.686 8.36971C172.099 8.36971 172.471 8.27367 172.801 8.08154C173.131 7.88941 173.389 7.62183 173.575 7.27879C173.768 6.93576 173.864 6.54467 173.864 6.10554C173.864 5.66641 173.768 5.27533 173.575 4.93229C173.389 4.58925 173.131 4.32167 172.801 4.12954C172.471 3.93741 172.099 3.84138 171.686 3.84138C171.273 3.84138 170.901 3.93741 170.571 4.12954C170.247 4.32167 169.989 4.58925 169.796 4.93229C169.611 5.27533 169.518 5.66641 169.518 6.10554C169.518 6.54467 169.611 6.93576 169.796 7.27879C169.989 7.62183 170.247 7.88941 170.571 8.08154C170.901 8.27367 171.273 8.36971 171.686 8.36971ZM178.188 8.36971C178.539 8.36971 178.863 8.30796 179.159 8.18446C179.462 8.05408 179.713 7.86542 179.913 7.61842L180.212 7.95804C179.978 8.24621 179.686 8.46575 179.334 8.61671C178.983 8.76079 178.598 8.83283 178.178 8.83283C177.641 8.83283 177.16 8.71621 176.733 8.48292C176.313 8.24962 175.983 7.92717 175.741 7.5155C175.508 7.10383 175.39 6.63383 175.39 6.10554C175.39 5.57725 175.501 5.10725 175.721 4.69558C175.948 4.28392 176.261 3.96146 176.66 3.72817C177.06 3.49487 177.511 3.37825 178.013 3.37825C178.481 3.37825 178.901 3.48805 179.273 3.70758C179.651 3.9203 179.951 4.21875 180.171 4.60296C180.398 4.98034 180.522 5.40571 180.542 5.87913L175.989 6.76421C176.12 7.25821 176.382 7.64929 176.774 7.93746C177.166 8.22563 177.638 8.36971 178.188 8.36971ZM178.013 3.83108C177.607 3.83108 177.242 3.92712 176.918 4.11925C176.595 4.3045 176.344 4.56867 176.165 4.91171C175.986 5.24792 175.896 5.63213 175.896 6.06438C175.896 6.13983 175.903 6.24621 175.917 6.38342L180.026 5.58067C179.978 5.25821 179.865 4.96317 179.686 4.69558C179.507 4.428 179.273 4.21875 178.983 4.06779C178.694 3.91 178.371 3.83108 178.013 3.83108ZM184.649 8.45204C184.518 8.57554 184.353 8.67158 184.153 8.74021C183.961 8.80196 183.757 8.83283 183.544 8.83283C183.076 8.83283 182.715 8.70592 182.46 8.45204C182.205 8.19134 182.078 7.83113 182.078 7.37142V2.24617H182.594V3.41942H184.308V3.86196H182.594V7.31996C182.594 7.66988 182.676 7.93746 182.842 8.12271C183.014 8.30108 183.265 8.39029 183.595 8.39029C183.933 8.39029 184.212 8.29079 184.432 8.09183L184.649 8.45204ZM190.682 3.41942V8.79167H190.176V7.52579C189.962 7.94434 189.663 8.26679 189.277 8.49321C188.892 8.71963 188.455 8.83283 187.966 8.83283C187.463 8.83283 187.009 8.71621 186.603 8.48292C186.197 8.24962 185.877 7.92717 185.643 7.5155C185.416 7.10383 185.302 6.63383 185.302 6.10554C185.302 5.57725 185.416 5.10725 185.643 4.69558C185.877 4.27704 186.197 3.95458 186.603 3.72817C187.009 3.49487 187.463 3.37825 187.966 3.37825C188.448 3.37825 188.878 3.49146 189.257 3.71788C189.642 3.94429 189.945 4.25992 190.165 4.66471V3.41942H190.682ZM187.997 8.36971C188.41 8.36971 188.778 8.27367 189.102 8.08154C189.432 7.88941 189.69 7.62183 189.876 7.27879C190.069 6.93576 190.165 6.54467 190.165 6.10554C190.165 5.66641 190.069 5.27533 189.876 4.93229C189.69 4.58925 189.432 4.32167 189.102 4.12954C188.778 3.93741 188.41 3.84138 187.997 3.84138C187.584 3.84138 187.212 3.93741 186.882 4.12954C186.558 4.32167 186.3 4.58925 186.108 4.93229C185.922 5.27533 185.829 5.66641 185.829 6.10554C185.829 6.54467 185.922 6.93576 186.108 7.27879C186.3 7.62183 186.558 7.88941 186.882 8.08154C187.212 8.27367 187.584 8.36971 187.997 8.36971Z" fill="#8AC44B"/>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="h-right">
                    <div id="header-calend"></div>
                    <div class="header-right">
                        <button id="export-button"
                                class="button button-raised button-fill button-round big-button button-green popover-open hidden"
                                data-popover=".popover-export"><i
                                    class="leadee-icon icon-export"></i><span><?php _e('Export',LEADEE_PLUGIN_NAME); ?></span>
                        </button>
                        <div class="top-icon-block" id="start-tour">
                            <i class="leadee-icon leadee-icon-big icon-question pulse-button"></i>
                        </div>
                        <a class="donate-top" href="https://donate.leadee.io" target="_blank">
                            <i class="leadee-icon leadee-icon-big icon-donate"></i>
                            <span><?php _e('Donate us',LEADEE_PLUGIN_NAME); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="popover popover-export">
        <div class="popover-angle"></div>
        <div class="popover-inner">
            <div class="list">
                <ul>
                    <li><button class="list-button popover-close export-button" data-type="xls"><i class="icon-excel" style="background-image: url('<?php echo LEADEE_PLUGIN_URL . '/core/assets'; ?>/image/excel.png')"></i><span>Excel table</span></button></li>
                    <li><button class="list-button popover-close export-button" data-type="csv"><i class="icon-csv" style="background-image: url('<?php echo LEADEE_PLUGIN_URL . '/core/assets'; ?>/image/csv.png')"></i><span>CSV table</span></button></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="panel panel-left panel-cover panel-init elevation-3">
        <div class="page no-swipe-panel">
            <div class="page-content sidebar-content" id="sidebar-links">
                <img class="sidebar-logo hidden-desktop"
                     src="<?php echo LEADEE_PLUGIN_URL . '/core/assets'; ?>/image/logo.png"
                     alt="">
                <div class="list links-list">
                    <ul>
                        <li>
                            <a href="<?php echo get_site_url(); ?>/?leadee-page=dashboard"><i
                                        class="menu-icon icon-bar"></i><span><?php _e('Dashboard',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                    </ul>
                </div>
                <div class="block-title"><?php _e('Work space',LEADEE_PLUGIN_NAME); ?></div>
                <div class="list links-list">
                    <ul>
                        <li>
                            <a href="<?php echo get_site_url(); ?>/?leadee-page=leads"><i
                                        class="menu-icon icon-leads"></i><span><?php _e('Leads',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                        <li>
                            <a href="<?php echo get_site_url(); ?>/?leadee-page=goals"><i
                                        class="menu-icon icon-goals"></i><span><?php _e('Goals',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                    </ul>
                </div>
                <div class="block-title"><?php _e('Plugin settings',LEADEE_PLUGIN_NAME); ?></div>
                <div class="list links-list">
                    <ul>
                        <li>
                            <a href="<?php echo get_site_url(); ?>/?leadee-page=leads-table-settings"><i
                                        class="menu-icon icon-leads-table"></i><span><?php _e('Leads table settings',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                        <li>
                            <a href="<?php echo get_site_url(); ?>/?leadee-page=goals-settings"><i
                                        class="menu-icon icon-ref"></i><span><?php _e('Goals settings',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                    </ul>
                </div>
                <div class="block-title"><?php _e('Support',LEADEE_PLUGIN_NAME); ?></div>
                <div class="list links-list">
                    <ul>
                        <li>
                            <a href="https://leadee.io/documentation/" target="_blank"><i
                                        class="menu-icon icon-support"></i><span><?php _e('Support & Faq',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                        <li>
                            <a href="https://donate.leadee.io" target="_blank"
                               class=""><i
                                        class="menu-icon icon-donate"></i><span><?php _e('Donate us',LEADEE_PLUGIN_NAME); ?></span></a>
                        </li>
                    </ul>
                </div>
                <div class="news-block">
                    <div class="news-block-title"><h4><i class="menu-icon icon-news"></i> <span><?php _e('Leadee news',LEADEE_PLUGIN_NAME); ?></span></h4></div>
                    <ul class="news-list" id="news-feed">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="content-block stroll-content">
        <div>
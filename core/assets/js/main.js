let currentUrlParams = new URLSearchParams(window.location.search);
(function ($) {
    saveTimezone();

    function saveTimezone() {
        var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        setStorageData("leadee_timezone", timezone);
    }

    function updateDate() {
        var dtFrom = getStorageData("dt_from");
        var dtTo = getStorageData("dt_to");

        if (!currentUrlParams.has("dt_from") && !currentUrlParams.has("dt_to") && (dtFrom === null || dtTo === null)) {
            var today = getToday().getTime().toString();
            var lastweek = getLastWeek().getTime().toString();
            updateUrl(lastweek, today);
            setStorageData("dt_from", lastweek)
            setStorageData("dt_to", today)
        } else {
            updateUrl(dtFrom, dtTo);
        }
    }

    function updateUrl(from, to) {
        currentUrlParams = addToCurrentUrlParam("dt_from", from);
        currentUrlParams = addToCurrentUrlParam("dt_to", to);
        updateUrlParams(currentUrlParams);
    }

    updateDate();

    $('body').on('click', 'a', function () {
        var href = $(this).attr("href");
        var attrTarget = $(this).attr('target');

        if (typeof attrTarget !== 'undefined' && attrTarget !== false) {
            window.open(href, "_blank")
        } else {
            window.open(href, "_self")
        }
    });

    function checkNewLeads() {
        var current_leads = parseInt(getStorageData("current_leads"));
        $.ajax({
            url: outData.siteUrl + LEADEE_API_PARAM + API_DASHBOARD_GET_LEADS_COUNTER,
            type: 'GET',
            success: function (serverData) {
                handleLeadsCounterResponse(current_leads, serverData)
            },
            error: function () {
            }
        });
    }

    window.setInterval(() => checkNewLeads(), 3000);

    function handleLeadsCounterResponse(current_leads, serverData) {
        var data = serverData.data;
        var allLeads = parseInt(data["allLeads"]);
        if (allLeads !== 0 && current_leads !== allLeads) {
            if (current_leads < allLeads) {
                showLastLeadAlert();
            }
            needReload = true;
            setStorageData("current_leads", allLeads)
        }
    }

    function showLastLeadAlert() {
        var timezone = getStorageData("leadee_timezone");
        $.ajax({
            type: 'GET',
            url: outData.siteUrl + LEADEE_API_PARAM + API_GET_LAST_LEAD_DATA + "&timezone=" + timezone,
            dataType: 'json',
            success: createLastLeadNotification
        });
    }

    function createLastLeadNotification(serverData) {
        var data = serverData.data;
        $(".notification.modal-in").html("");
        const {dt, text} = data;
        const notificationWithButton = app.notification.create({
            icon: '<i class="leadee-icon leadee-icon-big icon-bell" style="width: 40px !important;height: 40px !important;"></i>',
            title: localDataMain.newLead,
            subtitle: text,
            text: dt,
            closeButton: true,
        });

        notificationWithButton.open();
    }

    $('.export-button').on('click', function () {
        downloadFile($(this).attr('data-type'));
    });

    const downloadFile = (type) => {
        const currentUrlParams = new URLSearchParams(window.location.search);
        const urlDateFrom = currentUrlParams.get('dt_from');
        const urlDateTo = currentUrlParams.get('dt_to');
        const timezone = getStorageData("leadee_timezone");
        var serverDownloadUrl = `${outData.siteUrl}/wp-admin/admin-ajax.php?action=leadee_api&leadee-api=export&type=${type}&from=${urlDateFrom}&to=${urlDateTo}&timezone=${timezone}&draw=1&start=0&length=2500&order[0][column]=0&order[0][dir]=desc`;

        $.ajax({
            url: serverDownloadUrl,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    const dateFrom = new Date(parseInt(urlDateFrom));
                    const dateTo = new Date(parseInt(urlDateTo));
                    const formattedDateFrom = dateFrom.toISOString().slice(0, 10);
                    const formattedDateTo = dateTo.toISOString().slice(0, 10);
                    var fileName = 'leadee_export_' + formattedDateFrom + '_' + formattedDateTo + '.' + type;
                    var a = document.createElement('a');
                    a.href = 'data:application/octet-stream;base64,' + data.data.file;
                    a.download = fileName;
                    a.click();
                }
            }
        });
    }
        if ($(window).width() > 767) {
            $(".section-row").each(function () {
                var elements = $(this).find(">div");
                if (elements.length) {
                    var largestElementHeight = 0;
                    $(elements).each(function () {
                        var elementHeight = $(this).outerHeight();
                        if (elementHeight > largestElementHeight) {
                            largestElementHeight = elementHeight;
                        }
                    });
                    $(this).css("height", largestElementHeight);
                }
            });
        }

    $('body').on('click', 'a[data-panel=".panel-left"]', function() {
        $('.panel-left').addClass('panel-in');
    });

    $('body').on('click', '.stroll-content', function () {
        if ($('.panel-left').hasClass('panel-in')) {
            $('.panel-left').removeClass('panel-in');
        }
    });
})(jQuery);

function showChartMain(data) {
    if (window.mainChartBlock !== undefined) {
        window.mainChartBlock.destroy();
    }

    window.mainChartBlock = new Chart(document.getElementById("main-chart"), {
        type: 'bar',
        data: {
            labels: data["labels"],
            datasets: [{
                label: "",
                backgroundColor: data["colors"],
                data: data["data"]
            }]
        },
        plugins: [getEmptyPlugin(getFullTextForPlugin())],
        options: {
            plugins: {
                legend: {display: false}
            },
            title: {display: false},
            responsive: true,
            maintainAspectRatio: false,
            borderRadius: 16
        }
    });
    mainChartBlock.update();
}

function getEmptyPlugin(txt) {
    return {
        id: 'emptyChart',
        afterDraw(chart, args, options) {
            const {datasets} = chart.data;
            let hasData = false;
            for (let dataset of datasets) {
                if (dataset.data.some(item => item !== "0")) {
                    hasData = true;
                    break;
                }
            }
            if (!hasData) {
                const {chartArea: {left, top, right, bottom}, ctx} = chart;
                const centerX = left;
                const centerY = (top + bottom) / 4;

                chart.clear();
                ctx.save();
                ctx.fillStyle = '#635F6F';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = '16px Montserrat';
                ctx.textAlign = "left"
                const lineheight = 25;
                const lines = txt.split('\n');
                for (let i = 0; i < lines.length; i++) {
                    ctx.fillText(lines[i], centerX, centerY + (i * lineheight));
                }
                ctx.restore();
            }
        }
    };
}

function updateStatData(dateFrom, dateTo) {
    var timezone = getStorageData("leadee_timezone");
    var dateFromData = new Date(Number.parseInt(dateFrom)).getTime();
    var dateToData = new Date(Number.parseInt(dateTo)).getTime();
    jQuery.ajax({
        type: 'GET',
        url: `${outData.siteUrl}${LEADEE_API_PARAM}${API_DASHBOARD_GET_STAT_DATA}&from=${dateFromData}&to=${dateToData}&timezone=${timezone}`,
        dataType: 'json',
        success: (serverData) => {
            var data = serverData.data;
            showChartMain(data['dataMainChart']);
        },
    });
}

function getFullTextForPlugin() {
    return localDataMain.emptyLeadsTableText.replace( /<br\s*\/?>/gi, '\n' );
}


function getShortTextForPlugin() {
    return localDataMain.noDataText;
}

function addToCurrentUrlParam(key, val) {
    currentUrlParams = new URLSearchParams(currentUrlParams);
    if (currentUrlParams.has(key)) {
        currentUrlParams.delete(key);
    }
    currentUrlParams.append(key, val);
    return currentUrlParams;
}

function updateUrlParams(currentUrlParams) {
    window.history.replaceState(null, null, "?" + new URLSearchParams(currentUrlParams).toString());
}

function getToday(){
    return new Date();
}

function getLastWeek(){
    var today = getToday();
    return new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
}

function getLastMonth(){
    var today = getToday();
    return new Date(today.getFullYear(), today.getMonth(), today.getDate() - 30);
}

function openAlert(alertText) {


    const notificationWithButton = app.notification.create({
        icon: '<i class="leadee-icon leadee-icon-big icon-bell" style="width: 40px !important;height: 40px !important;"></i>',
        title: alertText,
        closeButton: true,
    });

    notificationWithButton.open();
}

//localStorage
function getStorageData(e) {
    return localStorage.getItem(e);
}

function setStorageData(e, t) {
    localStorage.setItem(e, t);
}

function deleteStorageData(e) {
    localStorage.removeItem(e);
}

//prealoader
window.onload = function() {
    var body = document.querySelector('body');
    body.classList.add('loaded-all-source');
}

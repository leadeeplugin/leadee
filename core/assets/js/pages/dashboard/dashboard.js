(function ($) {
    var currentDtFrom = currentUrlParams.get("dt_from");
    var currentDtTo = currentUrlParams.get("dt_to");

    $("#export-button").show();

    function updateStatData(dateFrom, dateTo) {
        currentDtFrom = currentUrlParams.get("dt_from");
        currentDtTo = currentUrlParams.get("dt_to");
        var dateFromData = new Date(Number.parseInt(dateFrom)).getTime();
        var dateToData = new Date(Number.parseInt(dateTo)).getTime();
        var timezone = getStorageData("leadee_timezone");
        $.ajax({
            type: 'GET',
            url: `${outData.siteUrl}${LEADEE_API_PARAM}${API_DASHBOARD_GET_STAT_DATA}&from=${dateFromData}&to=${dateToData}&timezone=${timezone}`,
            dataType: 'json',
            success: function (serverData) {
                var data = serverData.data;
                showChartMain(data["dataMainChart"]);
                showChartScreenSize(data["dataScreenSize"]);
                showChartSource(data["dataChartSource"]);
                showNewLeads(data["dataNewLeads"]);
                showTarget(data["countersData"]);
                showOsClients(data["osClients"]);
                showPopularPages(data["popularPages"]);
            }
        });
    }

    function showChartScreenSize(data) {

        if (window.chartScreenSizeBlock !== undefined) {
            window.chartScreenSizeBlock.destroy();
        }

        window.chartScreenSizeBlock = new Chart(document.getElementById("chartScreenSize"), {
            type: 'bar',
            data: {
                labels: data["labels"],
                datasets: [{
                    label: "",
                    backgroundColor: data["colors"],
                    data: data["data"],
                    type: 'bar'
                }]
            },
            plugins: [getEmptyPlugin(getShortTextForPlugin())],
            options: {
                plugins: {
                    legend: {display: false}
                },
                maintainAspectRatio: false,
                responsive: true,
                aspectRatio: 1.5,
                legend: {display: false},
                title: {
                    display: false,
                    text: ''
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            padding: 10
                        },
                        categoryPercentage: 0.1
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }
                }
            }
        });
        chartScreenSizeBlock.update();
    }

function showChartSource(data) {

    if (window.chartSource !== undefined) {
        window.chartSource.destroy();
    }

    window.chartSource = new Chart(document.getElementById("sourceChart"), {
        type: 'line',
        data: {
            labels: data["labels"],
            datasets: data["datasets"]
        },
        plugins: [getEmptyPlugin(getShortTextForPlugin())],
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            legend: {display: false},
            title: {
                display: false,
                text: ''
            },
            responsive: true,
            maintainAspectRatio: false,

            borderRadius: 16
        }
    });
    chartSource.update();
}

    function showNewLeads(data) {
        $("#leads-new-widget").html("");

        if (data.length === 0) {
            showNoDataMessage();
        } else {
            appendLeadsToWidget(data);
        }

    }

    function appendLeadsToWidget(data) {
        $.each(data, function (i, item) {
            var id = item["id"];
            var dt = item["dt"];
            var text = item["text"];
            var status = item["status"];
            var htmlStatus = (status == 1) ? "active" : "";

            var html = '<li data-id="' + id + '" class="' + htmlStatus + '">\n' +
                '                                        <div class="item-content">\n' +
                '                                        <a href="' + outData.siteUrl + '/?leadee-page=leads&show_table" class="item-inner">' +
                '                                                <div class="item-after">' + dt + '</div>\n' +
                '                                                <div class="item-title">' + text + '</div>\n' +
                '                                                <i class="icon-eye-open"></i>\n' +
                '                                            </a>\n' +
                '                                        </div>\n' +
                '                                    </li>';

            $("#leads-new-widget").append(html);
        });
    }

    function showNoDataMessage() {
        $("#leads-new-widget").html('<div class=\'no-data-last-leads\'><p><br><br>' + localDataDashboard.emptyNewLeads + '</p></div>');
    }

function showTarget(data){
    var isSet = data["isSet"];
    var targetUser = data["target"]["targetUser"];
    var targetCurrent = data["target"]["targetCurrent"];

    var counterToday = data["counters"]["counterToday"];
    var counterTodayDiff = data["counters"]["counterTodayDiff"];
    var counterYesterday = data["counters"]["counterYesterday"];
    var counterYesterdayDiff = data["counters"]["counterYesterdayDiff"];
    var counterWeek = data["counters"]["counterWeek"];
    var counterWeekDiff = data["counters"]["counterWeekDiff"];

    //counters
    $("#counter-today span>span").html(counterToday);
    setColorSup($("#counter-today span>sup"), counterTodayDiff);

    $("#counter-yesterday span>span").html(counterYesterday);
    setColorSup($("#counter-yesterday span>sup"), counterYesterdayDiff);

    $("#counter-week span>span").html(counterWeek);
    setColorSup($("#counter-week span>sup"), counterWeekDiff);


    //target
    if (isSet) {
        $("#target-user b").html("$" + targetUser);

        if (targetCurrent !== null) {
            $("#target-current b").html("$" + targetCurrent);
        }
    } else {
        $("#target-user").hide();
        $("#target-current").hide();
        $("#target-button").show();
        $("#target-none").show();
    }
    let percent = ((targetCurrent / targetUser) * 100).toFixed(1);
    if (percent > 100) {
        percent = 100;
    }

    var percentPart = percent / 100;

    const targetGauge = '.target-gauge';
    app.gauge.create({
        el: targetGauge,
        type: 'circle',
        value: percentPart,
        size: 250,
        borderColor: '#8AC44B',
        borderWidth: 10,
        valueText: `${percentPart}%`,
        valueFontSize: 41,
        valueTextColor: '#8AC44B'
    });

    var gauge = app.gauge.get(targetGauge);

    gauge.update({
        value: percentPart,
        valueText: `${percent}%`
    });
}

    function showOsClients(data) {
        var allItems = data["allItems"];
        if (data["items"].length === 0) {
            $("#os-clients").html(localDataDashboard.noDataText);
            return;
        }
        $("#os-clients").html("");
        $.each(data["items"], function (i, item) {
            var deviceOs = item["device_os"];
            var iconOsClass = getIconOsClass(deviceOs);
            $("#os-clients").append('<li><i class="' + iconOsClass + '"></i><span>' + deviceOs + '</span> <small>' + Math.round(item["count"] / allItems * 100) + '%</small></li>');
        });
    }

    function getIconOsClass(deviceOs) {
        if (deviceOs === 'MacOS') {
            return 'icon-macos';
        } else if (deviceOs === 'Windows') {
            return 'icon-windows';
        } else if (deviceOs === 'Android') {
            return 'icon-android';
        } else if (deviceOs === 'iOS') {
            return 'icon-ios';
        } else if (deviceOs === 'Linux') {
            return 'icon-linux';
        } else if (deviceOs === 'Symbian') {
            return 'icon-symbian';
        } else {
            return 'icon-os-unknown';
        }
    }

    function showPopularPages(data) {
        $("#popular-pages-data").html("");
        if (data.length) {
            $.each(data, function (i, item) {
                $("#popular-pages-data").append(`
                    <li class="row">
                        <div class="col-10">
                            <i class="icon-link"></i>
                        </div>
                        <div class="col-80 medium-50">
                            <a href="${item["url"]}" target="_blank">
                                <span>${item["title"]}</span>
                                <small>${item["urlRelative"]}</small>
                            </a>
                        </div>
                        <div class="col-40 hidden-mobile">
                            <div class="progressbar-block">
                                <div class="amount-block">
                                    <span class="el1">${item["count"]}</span>
                                    <span class="el2">${item["percent"]}%</span>
                                </div>
                                <div class="progressbar color-green" data-progress="${item["percent"]}">
                                    <span style="transform: translate3d(-${100 - item["percent"]}%, 0px, 0px);"></span>
                                </div>
                            </div>
                        </div>
                    </li>
                `);
            });
        } else {
            $("#popular-pages-data").append(localDataDashboard.noDataText);
        }
    }

    function setColorSup(element, counterDiff) {
        if (counterDiff === 0) {
            element.hide();
        } else if (counterDiff < 0) {
            element.html(counterDiff);
            element.addClass("counter_red");
        } else {
            element.html("+" + counterDiff);
            element.addClass("counter_green");
        }
    }

    updateStatData(currentDtFrom, currentDtTo);

    window.setInterval(() => checkForReload(), 500);

    function checkForReload(){
        if(needReload === true) {
            needReload = false;
            updateStatData(currentUrlParams.get("dt_from"), currentUrlParams.get("dt_to"));
        }
    }
})(jQuery);
(function ($) {
    var goalsTable = loadTable();
    updateStatData(currentUrlParams.get("dt_from"), currentUrlParams.get("dt_to"));
    updateTargetData();

    window.setInterval(() => checkForReload(), 500);

    function checkForReload() {
        if (needReload === true) {
            needReload = false;
            updateStatData(currentUrlParams.get("dt_from"), currentUrlParams.get("dt_to"));
            updateTargetData();
            goalsTable.draw();
        }
    }

    function updateTargetData() {

        $('#progress-bar').html();

        $.ajax({
            type: 'GET',
            url: outData.siteUrl + LEADEE_API_PARAM + API_LEADEE_DATA_TARGET_CURRENT,
            dataType: 'json',
            success: function (serverData) {
                var data = serverData.data;
                var month_target = data["month-target"];
                var leads_month_count = data["leads-month-count"];
                var leads_month_sum = data["leads-month-sum"];

                $('#target-progress-text').html('<span>' + localDataGoals.conversionText + ': ' + leads_month_count + '</span>');
                leads_month_sum = (leads_month_sum !== null) ? leads_month_sum : '0';
                $('#target-progress-num').html('<span>$' + leads_month_sum + '</span>');
                $('#month-target').html('<span>$ ' + month_target + '</span>');

                $('#goals-button').show();

                var percent = (parseFloat(leads_month_sum) / parseFloat(month_target)) * 100;
                percent = percent.toFixed(1);
                $('#current-percent').html(percent + '%');
                if (percent > 100) {
                    percent = 100;
                }
                changeProgress(percent);
            }
        });
    }



    function loadTable() {
        if (typeof ($.fn.DataTable) === 'undefined') {
            return;
        }

        const columns = [{data: 'title'}, {data: 'type'}, {data: 'count'}, {data: 'sum'}];

        var table = $('#leads-list-target').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${outData.siteUrl}${LEADEE_API_PARAM}${API_LEADEE_DATA_TARGET}`,
                type: 'GET',
                data: function (d) {
                    const dateFromData = new Date(parseInt(currentUrlParams.get('dt_from'))).getTime();
                    const dateToData = new Date(parseInt(currentUrlParams.get('dt_to'))).getTime();
                    d.from = dateFromData;
                    d.to = dateToData;
                }
            },
            dataSrc: "data",
            columns,
            order: [[0, 'desc']],
            dom: 'Blfrtip',
            buttons: [],
            iDisplayLength: 25,
            responsive: {
                details: {
                    type: 'column',
                },
            },
            columnDefs: [{
                className: 'dtr-control',
                orderable: false,
                targets: 0,
            }],
            language: {
                paginate: {
                    previous: '<span class="prev-datatable-icon"><i class="icon-light-left"></i></span>',
                    next: '<span class="next-datatable-icon"><i class="icon-light-right"></i></span>',
                },
                emptyTable: ' ',
            },
        });

        return table;
    }

})(jQuery);



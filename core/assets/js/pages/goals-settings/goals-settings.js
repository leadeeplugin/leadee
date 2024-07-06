(function ($) {
    function loadTable() {
        if (!$.fn.DataTable) {
            return;
        }

        let table = $("#leads-list-target-settings").DataTable({
            processing: true,
            serverSide: true,
            ajax: outData.siteUrl + LEADEE_API_PARAM + API_LEADEE_DATA_GOAL_SETTINGS,
            columns: [
                {
                    data: "title",
                    render: (datum, type, row) => row.title
                },
                {
                    data: "type",
                    render: (datum, type, row) => row.type
                },
                {
                    data: "status",
                    render: (datum, type, row) => {
                        const status = parseInt(row.status);
                        const checked = status === 1;
                        const statusText = checked ? localDataGoalsSettings.yesText : localDataGoalsSettings.notText;

                        return `
            <div class="status-input-holder">
              <label class="item-checkbox item-content row">
                <input type="checkbox" name="l-checkbox" data-id="${row.id}" data-status-from-db="${status}" ${checked ? "checked" : ""} />
                <i class="icon icon-checkbox"></i>
                <div class="item-inner">
                  <div class="item-title">${statusText}</div>
                </div>
              </label>
            </div>
          `;
                    }
                },
                {
                    data: "sum",
                    render: (datum, type, row) => {
                        let sum = row.sum || 1;
                        return `
            <div class="sum-input-holder stepper stepper-fill stepper-init"
                 data-wraps="true"
                 data-autorepeat="true"
                 data-autorepeat-dynamic="true"
                 data-decimal-point="2"
                 data-manual-input-mode="true">
              <div class="stepper-button-minus"></div>
              <div class="stepper-input-wrap">
                <input type="text"
                       value="${sum}"
                       min="0"
                       max="1000"
                       data-type="${row.type}"
                       data-id="${row.id}"
                       data-sum-from-db="${sum}"
                       step="1" />
              </div>
              <div class="stepper-button-plus"></div>
            </div>
          `;
                    }
                }
            ],
            columnDefs: [{visible: false}],
            language: {
                paginate: {
                    previous: '<span class="prev-datatable-icon"><i class="icon-light-left"></i></span>',
                    next: '<span class="next-datatable-icon"><i class="icon-light-right"></i></span>'
                },
                emptyTable: localDataGoalsSettings.noDataText
            }
        });

        table.on('xhr', function () {
            let data = table.ajax.json();
            if (!data || !data.data || !data.data.length) {
                $('.dataTables_paginate').hide();
            }
        });
    }

    loadTable();


    function updateTargetSetting(rows) {
        var data = {rows}
        $.ajax({
            type: 'POST',
            url: outData.siteUrl + LEADEE_API_PARAM + API_SAVE_TARGET_SETTING,
            data: data,
            success: function () {
                openAlert(localDataGoalsSettings.savedText);
            }
        });

    }

    var rows = $('#leads-list-target-settings tbody tr');
    var changedRows = [];

    rows.find('input[type="text"]').on('change', function () {
        var row = $(this).closest('tr');
        var rowData = {
            id: row.find('input[type="checkbox"]').data('id'),
            type: $(this).data('type'),
            sum: $(this).val()
        };
        changedRows.push(rowData);
    });


    $('body').on('click', '#target-settings-save-button', function () {
        var rows = [];
        $("#leads-list-target-settings tbody tr").each(function () {
            var statusEl = $(this).find('.status-input-holder input');
            var sumEl = $(this).find('.sum-input-holder input');
            var rowData = {
                type: sumEl.attr('data-type'),
                identifier: sumEl.attr('data-id'),
                cost: sumEl.val(),
                status: parseInt(getIntCheckboxStatus(statusEl))
            };
            rows.push(rowData);
        });


        updateTargetSetting(rows);
    });

    function getIntCheckboxStatus(el) {
        var status = 0;
        if (el.is(":checked")) {
            status = 1;
        }
        return status;
    }

    $('body').on('click', '.item-checkbox input', function () {
        var input = $(this);
        var itemCheckbox = input.closest('.item-checkbox');
        var itemTitle = itemCheckbox.find('.item-title');
        if (input.is(":checked")) {
            itemTitle.html(localDataGoalsSettings.yesText);
            input.attr('checked', 'checked');
        } else {
            itemTitle.html(localDataGoalsSettings.notText);
            input.removeAttr('checked');
        }
    });

    $.ajax({
        type: 'GET',
        url: outData.siteUrl + LEADEE_API_PARAM + API_LEADEE_DATA_TARGET_CURRENT,
        dataType: 'json',
        success: function (serverData) {
            var data = serverData.data;
            var month_target_sum = data["month-target"];
            $('#target-progress-num').html('<span>$' + month_target_sum + '</span>');
            $('#target-month-sum').val(month_target_sum);
            changeProgress(100);
        }
    });

    $('body').on('click', '#target-month-sum-save-button', function () {

        var leads_month_sum = $('#target-month-sum').val();
        var postForm = {
            'sum': leads_month_sum
        };

        $.ajax({
            type: 'POST',
            url: outData.siteUrl + LEADEE_API_PARAM + API_TARGET_MONTH_SUM_SAVE,
            dataType: 'json',
            data: postForm,
            success: function () {
                $('#target-progress-num').html('<span>$' + leads_month_sum + '</span>');
                openAlert(localDataGoalsSettings.savedText);
            }
        });

    });
    $('body').on('click', '.stepper-button-minus', function () {
        var parentDiv = $(this).closest('.sum-input-holder');
        var stepperInput = parentDiv.find('.stepper-input-wrap input');
        var currentStepperInputValue = parseInt(stepperInput.val());
        if (currentStepperInputValue > 0) {
            stepperInput.val((currentStepperInputValue - 1).toFixed(2));
        }
    });

    $('body').on('click', '.stepper-button-plus', function () {
        var parentDiv = $(this).closest('.sum-input-holder');
        var stepperInput = parentDiv.find('.stepper-input-wrap input');
        var currentStepperInputValue = parseInt(stepperInput.val());
        stepperInput.val((currentStepperInputValue + 1).toFixed(2));
    });

})(jQuery);

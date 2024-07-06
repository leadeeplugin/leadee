(function( $ ) {
    $("#export-button").show();
    var leadsListSelector = '#leads-list';
    var leadListTable = $(leadsListSelector).DataTable(getTableConfig());

    function getColumnFields() {
        const column = {
            name: "fields",
            data: "fields",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                const fields = JSON.parse(row.fields);
                let html = '<div class="fields-data">';

                if (Array.isArray(fields) && fields.length !== 0) {
                    fields.forEach(field => {
                        const fieldName = field.field_name;
                        const value = field.value;
                        if (typeof fieldName === "string" && fieldName.length > 0) {
                            html += `<span><b>${fieldName}: </b>${value}</span><br>`;
                        } else {
                            html += `<span>${value}</span><br>`;
                        }
                    });
                }
                html += '<div>';
                return html;
            }
        };

        return column;
    }

    function getColumnFirstUrlParameters() {
        const column = {
            name: "first_url_parameters",
            data: "first_url_parameters",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                const firstUrlParams = JSON.parse(row.first_url_parameters);
                let html = '';

                if (Array.isArray(firstUrlParams) && firstUrlParams.length) {
                    html = firstUrlParams
                        .map(param => `<span>${decodeURI(param)}</span><br>`)
                        .join('');
                }

                return html;
            }
        };

        return column;
    }

    function getColumnDeviceBrowser() {
        return {
            name: "device_browser_name",
            data: "device_browser",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                return `${row.device_browser_name} ${row.device_browser_version}`;
            }
        };
    }

    function getColumnDeviceScreenSize() {
        return {
            name: "device_width",
            data: "device_screen_size",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                return `${row.device_width}x${row.device_height}`;
            }
        };
    }

    function getColumnPost() {
        return {
            name: "post_id",
            data: "post",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                const postType = row.post_type !== 'post' ? row.post_type : '';
                const url = row.post_id > 0 ? `${row.home_url}?post_type=${postType}&p=${row.post_id}` : row.home_url;
                return `<a href="${url}" target="_blank">${row.post_name}</a>`;
            }
        };
    }

    function getColumnDeviceOs() {
        return {
            name: "device_os",
            data: "device_os",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                var html = row.device_os + ' ' + row.device_os_version;
                return html;
            }
        };
    }

    function getColumnFormName() {
        const formUrls = {
            'wpforms': '/wp-admin/admin.php?page=wpforms-builder&view=fields&form_id=',
            'ninja': '/wp-admin/admin.php?page=ninja-forms&form_id='
        };

        return {
            name: "form_id",
            data: 'form_name',
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                const formType = row.form_type;
                const formUrl = formUrls[formType] || '/wp-admin/admin.php?page=wpcf7&post=';
                const url = row.home_url + formUrl + row.form_id + '&action=edit';

                const html = `<a href="${url}" target="_blank">${row.form_name}</a>`;
                return html;
            }
        };
    }

    function getColumnCost() {
        return {
            name: "cost",
            data: "cost",
            orderable: false,
            sortable: false,
            render: function (datum, type, row) {
                var html = row.cost;
                return html;
            }
        };
    }

    function getTableConfig() {
        if (!$.fn.DataTable) {
            return;
        }

        const columnDt = {data: 'dt', orderable: false, sortable: false};
        const columnFields = getColumnFields();
        const columnSourceCategory = {data: 'source_category', orderable: false, sortable: false};
        const columnSource = {data: 'source', orderable: false, sortable: false};
        const columnFirstUrlParameters = getColumnFirstUrlParameters();
        const columnDeviceBrowser = getColumnDeviceBrowser();
        const columnDeviceScreenSize = getColumnDeviceScreenSize();
        const columnDeviceType = {data: 'device_type', orderable: false, sortable: false};
        const columnPost = getColumnPost();
        const columnDeviceOs = getColumnDeviceOs();
        const columnFormType = {data: 'form_type', orderable: false, sortable: false};
        const columnFormName = getColumnFormName();
        const columnCost = getColumnCost();

        const columns = [
            ...(outData.isEnableColumnDt ? [columnDt] : []),
            columnFields,
            columnSourceCategory,
            columnDeviceType,
            columnPost,
            columnDeviceOs,
            columnFormType,
            columnFormName,
            ...(outData.isEnableColumnSource ? [columnSource] : []),
            ...(outData.isEnableColumnFirstUrlParameters ? [columnFirstUrlParameters] : []),
            ...(outData.isEnableColumnDeviceBrowser ? [columnDeviceBrowser] : []),
            ...(outData.isEnableColumnDeviceScreenSize ? [columnDeviceScreenSize] : []),
            columnCost,
        ];
        currentUrlParams = new URLSearchParams(window.location.search);


        var entries = parseInt(currentUrlParams.get('entries'));

        if (entries === undefined || isNaN(entries)) {
            entries = 25;
        }

        $.fn.dataTable.ext.search.push(
            function (settings, data) {
                var filterValue = $('.select-type-filter').val().toLowerCase();
                var columnValue = data[4].toLowerCase();
                return columnValue.indexOf(filterValue) !== -1;
            }
        );

        var timezone = getStorageData("leadee_timezone");

        const tableConfig = {
            columnDefs: [
                {"orderable": false, "targets": 0}
            ],
            destroy: true,
            lengthChange: true,
            pageLength: entries,
            processing: true,
            serverSide: true,
            ajax: {
                url: `${outData.siteUrl}${LEADEE_API_PARAM}${API_LEADEE_DATA}&timezone=${timezone}`,
                type: 'GET',
                data: function (d) {
                    var filter = currentUrlParams.get('filter');
                    if (filter === undefined) {
                        filter = '';
                    }
                    const dateFromData = new Date(parseInt(currentUrlParams.get('dt_from'))).getTime();
                    const dateToData = new Date(parseInt(currentUrlParams.get('dt_to'))).getTime();
                    d.filter = filter;
                    d.from = dateFromData;
                    d.to = dateToData;
                }
            },
            dataSrc: 'data',
            columns,
            dom: 'Blfrtip',
            orderable: false,
            sortable: false,
            buttons: [],
            responsive: {
                details: {
                    type: 'column',
                },
            },
            language: {
                paginate: {
                    previous: '<span class="prev-datatable-icon"><i class="icon-light-left"></i></span>',
                    next: '<span class="next-datatable-icon"><i class="icon-light-right"></i></span>',
                },
                emptyTable: ' ',
            },
        };

        return tableConfig;
    }


    updateStatData(currentUrlParams.get("dt_from"), currentUrlParams.get("dt_to"));

    $(document).ready(function () {
        var filter = currentUrlParams.get('filter');
        var filterBlock = '<div class="row leads-table-filter"  id="datatable-filters">'
            + getHtmlSelect(filter, 'entries', 'entries', 0, localDataLeads.Entries + ':', ['25', '50', '100'], 15)
            + getHtmlSelect(filter, 'source_category', 'filter', 4, localDataLeads.Source + ':', ['', 'advert', 'social', 'referal', 'direct', 'All'], 25)
            + getHtmlSelect(filter, 'device_type', 'filter', 4, localDataLeads.Device + ':', ['', 'mobile', 'desktop', 'All'], 25)
            + getHtmlResetButton()
        '</div>';
        $('#leads-filter-block').before(filterBlock);
        $('#datatable-filters select').on('change', function () {
            var parentFilter = $(this).closest('#datatable-filters');
            var entries = $('#datatable-filters select[data-type="entries"]').val();
            var filter = ''
            $(parentFilter.find('select.select-type-filter')).each(function () {
                var value = $(this).val();
                if (value !== '') {
                    filter += $(this).attr('data-type') + '%23' + $(this).val() + '%3B';
                }
            });

            updateUrlParamsWithFilter(entries, filter)
            leadListTable.page.len(entries).draw();
            leadListTable.draw();
        });
        $('#reset-filter-button').on('click', function () {
            currentUrlParams = addToCurrentUrlParam("filter", '');
            updateUrlParams(currentUrlParams);
            leadListTable.draw();
            $('.select-type-filter').prop('selectedIndex', 0);
        });

    });

    function updateUrlParamsWithFilter(entries, filter) {
        currentUrlParams = addToCurrentUrlParam("entries", entries);
        currentUrlParams = addToCurrentUrlParam("filter", filter);
        updateUrlParams(currentUrlParams);
    }

    window.setInterval(() => checkForReload(), 500);

    function checkForReload() {
        if (needReload === true) {
            needReload = false;
            updateStatData(currentUrlParams.get("dt_from"), currentUrlParams.get("dt_to"));
            leadListTable.draw();
        }
    }

})(jQuery);


function getHtmlSelect(filter, type, classType, data_num_column, name, data, column_size) {
    return '<div class="col-100 medium-' + column_size + '"><div class="dataTables_additional_filter item-inner inner-center">\n' +
        '<div class="item-title item-label">' + name + '</div>\n' +
        '<div class="item-input-wrap input-dropdown-wrap">\n' +
        '<select placeholder="Please choose..." class="select-type-' + classType + '" data-type="' + type + '">\n' +
        getSelectHtml(filter, data) +
        '</select>\n' +
        '</div>' +
        '</div>' +
        '</div>';
}

function getHtmlResetButton() {
    return '<div class="col-100 medium-25"><div class="dataTables_additional_filter item-inner inner-center">\n' +
        '<button class="reset-filter-button" id="reset-filter-button">' + localDataLeads.ResetFilter + '</button>\n' +
        '</div>' +
        '</div>';
}

function getSelectHtml(filter, data) {
    var html = '';
    if (data.length > 0) {
        data.forEach((element) => {
            if (element === '') {
                html = '<option value="">' + localDataLeads.Select + '</option>';
            } else {
                var element_value = element;
                if (element === 'All') {
                    element_value = '';
                }
                var isSelected = (filter !== null && filter !== undefined  && element_value.length > 0) ? filter.includes(element_value) : false;
                html += '<option value="' + element_value + '" ' + ((isSelected) ? 'selected' : '') + '>' + element + '</option>';
            }

        });
    }
    return html;
}

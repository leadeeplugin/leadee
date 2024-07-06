(function( $ ) {
    $('.js-switch').on('click', function () {
        var option = $(this).attr('data-type');
        var value = 0;
        if ($(this).is(":checked")) {
            value = 1;
        }
        $.ajax({
            url: outData.siteUrl + LEADEE_API_PARAM + API_SETTINGS_SET_OPTION_VALUE,
            type: 'post',
            data: {'type': 'leads-table-columns', 'option': option, 'value': value},
            dataType: 'JSON',
            success: function () {
                openAlert(localDataLeadsTableSettings.savedText);
            }
        });

    });


})( jQuery );

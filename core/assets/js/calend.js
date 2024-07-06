var needReload = false;
(function ($) {
	var currentUrlParams = new URLSearchParams( window.location.search );
	$( "#header-calend, #header-calend" ).html( getCalendButtonHtml() );

	var urlDateFrom = new Date( Number.parseInt( currentUrlParams.get( "dt_from" ) ) );
	var urlDateTo   = new Date( Number.parseInt( currentUrlParams.get( "dt_to" ) ) );

	var from = moment( urlDateFrom ).format( 'MM/DD/YYYY' );
	var to   = moment( urlDateTo ).format( 'MM/DD/YYYY' );
	$( ".calend-from" ).html( from );
	$( ".calend-to" ).html( to );

	let dpMin, dpMax, rangeCalend;

	$( '.open-calendar' ).on(
		'click',
		function () {

			if ($( '.calend-block.modal-in' ).length === 0) {

				popup = app.popup.create(
					{
						content: "<div class=\"popup calend-block\">\n      <div class=\"calend-holder\">\n        <div class=\"calend-body row\">\n          <div class=\"col-100 medium-50 calend-child mobile-hidden\">\n            <span>" + localDataCalend.From + ": </span><input id=\"calend1\" value=\"\"/>\n          </div>\n          <div class=\"col-100 medium-50 calend-child mobile-hidden\">\n            <span>" + localDataCalend.To + ": </span><input id=\"calend2\" value=\"\"/>\n          </div>\n          <div class=\"col-100 medium-100 calend-child calend-range\">\n            <input id=\"range-calend\" type=\"hidden\"/>\n          </div>\n        </div>\n        <div class=\"calend-footer segmented\">\n          <button class=\"button button-filter filter-today\">" + localDataCalend.Today + "</button>\n          <button class=\"button button-filter filter-week\">" + localDataCalend.text7days + "</button>\n          <button class=\"button button-filter filter-month\">" + localDataCalend.text31days + "</button>\n          <span class=\"segmented-highlight\"></span>\n        </div>\n      </div>\n    </div>"
					}
				);
				popup.open( false );

				var unixTimeUrlFrom = Number.parseInt( currentUrlParams.get( "dt_from" ) );
				var unixTimeUrlTo   = Number.parseInt( currentUrlParams.get( "dt_to" ) );
				urlDateFrom         = new Date( unixTimeUrlFrom );
				urlDateTo           = new Date( unixTimeUrlTo );

				if (window.outerWidth > 768) {
					dpMin                      = new AirDatepicker(
						'#calend1',
						{
							locale: airDatepickerLanguage,
							inline: true,
							onSelect( {date} ) {
								var dateFormat = moment( date ).format( 'MM/DD/YYYY' );
								dpMax.update(
									{
										minDate: dateFormat
									}
								)
							updateFrom( date );
							updateUrlParams( currentUrlParams );
							}
						}
					)

					dpMax                      = new AirDatepicker(
						'#calend2',
						{
							locale: airDatepickerLanguage,
							inline: true,
							onSelect( {date} ) {
								var dateFormat = moment( date ).format( 'MM/DD/YYYY' );
								dpMin.update(
									{
										maxDate: dateFormat
									}
								)
							$( ".calend-to" ).html( dateFormat );

							if (urlDateTo.getTime().toString() !== date.getTime().toString()) {
								updateTo( date );
								updateUrlParams( currentUrlParams );
								needReload = true;
								popup.close();
							}

							}
						}
					)

					dpMin.update(
						{
							maxDate: getToday()
						}
					)

					changeDate( urlDateFrom, urlDateTo );
					setActiveCustomDateButton( urlDateFrom, urlDateTo );
				} else {
					rangeCalend                     = new AirDatepicker(
						'#range-calend',
						{
							locale: airDatepickerLanguage,
							inline: true,
							range: true,
							multipleDatesSeparator: '-',
							onSelect( {date} ) {
								var calendRangeDate = $( '#range-calend' ).val();
								var dateRangeSplit  = calendRangeDate.split( "-" );
								var dateFrom        = moment( dateRangeSplit[0], 'DD.MM.YYYY' ).unix() * 1000;
								var dateTo          = moment( dateRangeSplit[1], 'DD.MM.YYYY' ).unix() * 1000;
								if (dateFrom !== unixTimeUrlFrom) {
									updateCalend( dateFrom, dateTo );
									if (dateRangeSplit.length === 2) {
										needReload = true;
										popup.close();
									}
								}
							}
						}
					);

					rangeCalend.update(
						{
							maxDate: getToday()
						}
					)

					changeDate( urlDateFrom, urlDateTo );
					setActiveCustomDateButton( urlDateFrom, urlDateTo );
				}

			} else {
				$( '.popup-backdrop.backdrop-in' ).remove();
				$( '.calend-block.modal-in' ).remove();
			}
		}
	);

	$( 'body' ).on(
		'click',
		'.filter-today',
		function () {
			var today = getToday();
			changeDate( today, today );
		}
	);

	$( 'body' ).on(
		'click',
		'.filter-week',
		function () {
			changeDate( getLastWeek(), getToday() );
		}
	);

	$( 'body' ).on(
		'click',
		'.filter-month',
		function () {
			changeDate( getLastMonth(), getToday() );
		}
	);

	function setActiveCustomDateButton(dateFrom, dateTo) {
		var dateFromDate = moment( dateFrom );
		var dateToDate   = moment( dateTo );

		var diffDays = dateToDate.diff( dateFromDate, 'days' );

		if (diffDays === 0) {
			$( '.filter-today' ).addClass( 'active' );
		}

		if (diffDays === 6) {
			$( '.filter-week' ).addClass( 'active' );
		}

		if (diffDays === 30) {
			$( '.filter-month' ).addClass( 'active' );
		}
	}

	function changeDate(dateFrom, dateTo) {
		dpMin.selectDate( dateFrom );
		dpMax.selectDate( dateTo );
		updateCalend( dateFrom, dateTo );
	}

	function updateCalend(dateFrom, dateTo) {
		if (dateFrom) {
			updateFrom( dateFrom );
		}
		if (dateTo) {
			updateTo( dateTo );
		}
		updateUrlParams( currentUrlParams );
		setStorageData( "dt_from", new Date( dateFrom ).getTime().toString() );
		setStorageData( "dt_to", new Date( dateTo ).getTime().toString() );
	}

	function updateFrom(dateFrom) {
		currentUrlParams = addToCurrentUrlParam( "dt_from", moment( dateFrom ).unix() * 1000 );
		$( ".calend-from" ).html( moment( dateFrom ).format( 'MM/DD/YYYY' ) );
		setStorageData( "dt_from", new Date( dateFrom ).getTime().toString() );
	}

	function updateTo(dateTo) {
		currentUrlParams = addToCurrentUrlParam( "dt_to", moment( dateTo ).unix() * 1000 );
		$( ".calend-to" ).html( moment( dateTo ).format( 'MM/DD/YYYY' ) );
		setStorageData( "dt_to", new Date( dateTo ).getTime().toString() );
	}

	function getCalendButtonHtml() {
		return '<div  class="calend"><button class="open-calendar"><div><span class="calend-from"></span> - <span class="calend-to"></span></div>\n' +
			'         <i class="leadee-icon leadee-icon-big icon-calend"></i></button>\n' +
			'       </div>';
	}
})( jQuery );

const LEADEE_API_PARAM                = '/wp-admin/admin-ajax.php?action=leadee_api&leadee-api=';
const API_DASHBOARD_GET_LEADS_COUNTER = 'dashboard-get-leads-counter';

jQuery( document ).ready(
	function ($) {
		function fetchUnreadCount() {
			var currentLeads = parseInt( localStorage.getItem( 'current_leads' ) ) || 0;

			$.ajax(
				{
					url: outData.siteUrl + LEADEE_API_PARAM + API_DASHBOARD_GET_LEADS_COUNTER,
					type: 'get',
					success: function (response) {
						if (response.success && response.data && typeof response.data.allLeads !== 'undefined') {
							var allLeads    = parseInt( response.data.allLeads );
							var unreadCount = Math.max( allLeads - currentLeads, 0 );

							var unreadCountElement = $( '#leadee-unread-count' );
							unreadCountElement.removeClass(
								function (index, className) {
									return (className.match( /(^|\s)count-\d+/g ) || []).join( ' ' );
								}
							);

							unreadCountElement.addClass( 'count-' + unreadCount );
							$( '#leadee-unread-count .plugin-count' ).text( unreadCount );
						} else {
							console.error( 'Failed to fetch unread leadee count' );
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.error( 'Error fetching leadee count: ' + textStatus, errorThrown );
					}
				}
			);
		}

		fetchUnreadCount();
		setInterval( fetchUnreadCount, 30000 );
	}
);

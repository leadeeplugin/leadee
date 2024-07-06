function openTour() {
	driver.defineSteps(
		[

			{
				element: '#leads-table-setting-for-tour',
				popover: {
					title: localDataLeadsTableSettingsTour.stepOneTitle,
					description: localDataLeadsTableSettingsTour.stepOneDesc,
					showButtons: true,
					position: 'bottom'
				},

			}
		]
	);

	driver.start();
}

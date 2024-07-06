function openTour() {
	driver.defineSteps(
		[

		{
			element: '#main-chart',
			popover: {
				title: localDataLeadsTour.stepOneTitle,
				description: localDataLeadsTour.stepOneDesc,
				showButtons: true,
				position: 'bottom'
			},

		}
		]
	);

	driver.start();
}
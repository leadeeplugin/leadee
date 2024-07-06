function openTour() {
	driver.defineSteps(
		[
			{
				element: '#main-chart',
				popover: {
					title: localDataGoalsTour.stepOneTitle,
					description: localDataGoalsTour.stepOneDesc,
					showButtons: true,
					position: 'right'
				}
			},
			{
				element: '#goals-status',
				popover: {
					title: localDataGoalsTour.stepTwoTitle,
					description: localDataGoalsTour.stepTwoDesc,
					showButtons: true,
					position: 'left'
				}
			},
			{
				element: '#table-leads-target',
				popover: {
					title: localDataGoalsTour.stepThreeTitle,
					description: localDataGoalsTour.stepThreeDesc,
					showButtons: true,
					position: 'bottom'
				}
			}
		]
	);

	driver.start();
}

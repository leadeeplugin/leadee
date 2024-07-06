function openTour() {
	driver.defineSteps(
		[
			{
				element: '#main-chart',
				popover: {
					className: 'popover-class',
					title: localDataDashboardTour.stepOneTitle,
					description: localDataDashboardTour.stepOneDesc,
					showButtons: true,
					position: 'bottom'
				}
			},
			{
				element: '#new-leads',
				popover: {
					className: 'popover-class',
					title: localDataDashboardTour.stepTwoTitle,
					description: localDataDashboardTour.stepTwoDesc,
					showButtons: true,
					position: 'left'
				}
			},
			{
				element: '.card#target-plan',
				popover: {
					className: 'popover-class',
					title: localDataDashboardTour.stepThreeTitle,
					description: localDataDashboardTour.stepThreeDesc,
					showButtons: true,
					position: 'right'
				}
			},
			{
				element: '#leads-sources-block',
				popover: {
					className: 'popover-class',
					title: localDataDashboardTour.stepFourTitle,
					description: localDataDashboardTour.stepFourDesc,
					showButtons: true,
					position: 'bottom'
				}
			},
			{
				element: '#popular-screen-sizes',
				popover: {
					className: 'popover-class',
					title: localDataDashboardTour.stepFiveTitle,
					description: localDataDashboardTour.stepFiveDesc,
					showButtons: true,
					position: 'bottom'
				}
			}
		]
	);

	driver.start();
}

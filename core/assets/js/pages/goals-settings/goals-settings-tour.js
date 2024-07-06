function openTour() {
	driver.defineSteps(
		[
		{
			element: '#leads-list-target-settings',
			popover: {
				title: localDataGoalsSettingsTour.stepOneTitle,
				description: localDataGoalsSettingsTour.stepOneDesc,
				showButtons: true,
				position: 'bottom'
			},
		},
		{
			element: '#goal-setting',
			popover: {
				title: localDataGoalsSettingsTour.stepTwoTitle,
				description: localDataGoalsSettingsTour.stepTwoDesc,
				showButtons: true,
				position: 'left'
			},
		}
		]
	);

	driver.start();
}
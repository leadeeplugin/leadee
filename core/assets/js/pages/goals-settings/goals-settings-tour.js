function openTour() {
    var driver = new Driver({
        animate: true,                    // Whether to animate or not
        opacity: 0.75,                    // Background opacity (0 means only popovers and without overlay)
        padding: 10,                      // Distance of element from around the edges
        allowClose: true,                 // Whether the click on overlay should close or not
        overlayClickNext: false,          // Whether the click on overlay should move next
        doneBtnText: 'End tour',              // Text on the final button
        closeBtnText: 'Close',            // Text on the close button for this step
        stageBackground: '#ffffff',       // Background color for the staged behind highlighted element
        nextBtnText: 'Next',              // Next button text for this step
        prevBtnText: 'Previous',          // Previous button text for this step
        showButtons: true,               // Do not show control buttons in footer
        keyboardControl: true,            // Allow controlling through keyboard (escape to close, arrow keys to move)
        scrollIntoViewOptions: {},        // We use `scrollIntoView()` when possible, pass here the options for it if you want any
    });

    // Define the steps for introduction
    driver.defineSteps([
        {
            element: '#leads-list-target-settings',
            popover: {
                title: 'Form Cost Settings Table',
                description: 'In this table, you can set the cost of leads for each form.',
                showButtons: true,
                position: 'bottom'
            },
        },
        {
            element: '#goal-setting',
            popover: {
                title: 'Want to earn a lot in a month? Set a monthly goal!',
                description: 'This setting will help you track your monthly progress.',
                showButtons: true,
                position: 'left'
            },
        }
    ]);

    driver.start();
}
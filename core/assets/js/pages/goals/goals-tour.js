function openTour() {
    var driver = new Driver({
        animate: true,                    // Whether to animate or not
        opacity: 0.75,                    // Background opacity (0 means only popovers and without overlay)
        padding: 10,                      // Distance of element from around the edges
        allowClose: true,                 // Whether the click on overlay should close or not
        overlayClickNext: false,          // Whether the click on overlay should move next
        doneBtnText: 'End tour',          // Text on the final button
        closeBtnText: 'Close',            // Text on the close button for this step
        stageBackground: '#ffffff',       // Background color for the staged behind highlighted element
        nextBtnText: 'Next',              // Next button text for this step
        prevBtnText: 'Previous',          // Previous button text for this step
        showButtons: true,               // Do not show control buttons in footer
        keyboardControl: true,            // Allow controlling through keyboard (escape to close, arrow keys to move)
        scrollIntoViewOptions: {},        // We use `scrollIntoView()` when possible, pass here the options for it if you want any
    });

    driver.defineSteps([
        {
            element: '#main-chart',
            popover: {
                title: 'Statistics Chart',
                description: 'The columns are colored with 7 rainbow colors for better perception',
                showButtons: true,
                position: 'right'
            }
        },
        {
            element: '#goals-status',
            popover: {
                title: 'Status of Your Goal Progress',
                description: 'Track your progress with our fun indicator',
                showButtons: true,
                position: 'left'
            }
        },
        {
            element: '#table-leads-target',
            popover: {
                title: 'Sum of Goals by Forms',
                description: 'See how much you earned from each form',
                showButtons: true,
                position: 'bottom'
            }
        }
    ]);

    driver.start();
}
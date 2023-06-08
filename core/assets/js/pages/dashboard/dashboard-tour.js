function openTour() {
    var driver = new Driver({
        className: 'scoped-class',        // className to wrap driver.js popover
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
            element: '#main-chart',
            popover: {
                className: 'popover-class',
                title: 'Chart with Statistics',
                description: 'The columns are colored in 7 rainbow colors for ease of perception.',
                showButtons: true,
                position: 'bottom'
            }
        },
        {
            element: '#new-leads',
            popover: {
                className: 'popover-class',
                title: 'New Leads Widget',
                description: 'Here you can always see notifications about new leads received in real-time. The information is also duplicated in a pop-up window.',
                showButtons: true,
                position: 'left'
            }
        },
        {
            element: '.card#target-plan',
            popover: {
                className: 'popover-class',
                title: 'You have reached customizable goals',
                description: 'What is it? - The uniqueness of our plugin. Set your own goals for the number of leads you want to receive per current month and achieve them!',
                showButtons: true,
                position: 'right'
            }
        },
        {
            element: '#leads-sources-block',
            popover: {
                className: 'popover-class',
                title: 'Leads Sources Chart',
                description: 'In the "Leads Sources" chart, you will see which type of traffic is the most conversion-friendly on your website: search, advertising systems, social networks, and others.',
                showButtons: true,
                position: 'bottom'
            }
        },
        {
            element: '#popular-screen-sizes',
            popover: {
                className: 'popover-class',
                title: '3 Blocks with Important Customer Information',
                description: 'Their screen sizes, the operating systems they use, and the pages from which they most often leave leads.',
                showButtons: true,
                position: 'bottom'
            }
        }
    ]);

    driver.start();
}
var driver = new Driver(
    {
        animate: true,                    // Whether to animate or not
        opacity: 0.75,                    // Background opacity (0 means only popovers and without overlay)
        padding: 10,                      // Distance of element from around the edges
        allowClose: true,                 // Whether the click on overlay should close or not
        overlayClickNext: false,          // Whether the click on overlay should move next
        doneBtnText: localDataCommonTour.Endtour,              // Text on the final button
        closeBtnText: localDataCommonTour.CloseText,            // Text on the close button for this step
        stageBackground: '#ffffff',       // Background color for the staged behind highlighted element
        nextBtnText: localDataCommonTour.Next,              // Next button text for this step
        prevBtnText: localDataCommonTour.Previous,          // Previous button text for this step
        showButtons: true,               // Do not show control buttons in footer
        keyboardControl: true,            // Allow controlling through keyboard (escape to close, arrow keys to move)
        scrollIntoViewOptions: {},        // We use `scrollIntoView()` when possible, pass here the options for it if you want any
    }
);

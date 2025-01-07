// Add a resize event listener to the window and pass a debounce function
window.addEventListener("resize", debounce(() => {
    // Obtain the innerWidth of the window
    let w = window.innerWidth;

    // Get the button to toggle the layout
    let b = document.querySelector('button[name="view"]');

    // If the button exists
    if (b) {
        // Get value from button, which can be list or grid
        let m = b.value;

        // If the window size is smaller or equal than 1024 and the button has list as value
        if (w <= 1024 && m == "list") {
            // Click the layout button
            b.click();
        }
        
        // If the window size is greater than 1024, and the button has grid as value
        if (w > 1024 && m == "grid") {
            // Click the layout button
            b.click();        
        }
    }
}, 20));

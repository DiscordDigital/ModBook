// Waits for an element with class "actions" to be created
waitFor(".actions", () => {
    // If the right sidebar contains no items
    if (document.querySelectorAll(".tri-layout-right-contents .actions .icon-list-item").length == 0) {
        // Try to fetch right sidebar .actions panel
        const actionsPanel = document.querySelector(".tri-layout-right-contents .actions");

        // If the panel exists
        if (actionsPanel) {
            // Remove the panel, because it is empty
            actionsPanel.remove();
        }
    }
});

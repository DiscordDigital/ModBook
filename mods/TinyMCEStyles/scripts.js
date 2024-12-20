// Listen to BookStack emmitted WYSWIYG editor setup event
window.addEventListener('editor-tinymce::setup', event => {
    // Get editor from event
    const editor = event.detail.editor;
    
    // Wait for init event
    editor.on('init', function() {
        // Create new link element
        const cssLink = document.createElement("link");
        // Add attributes
        cssLink.setAttribute("rel", "stylesheet");
        cssLink.setAttribute("type", "text/css");
        cssLink.setAttribute("href", "/uploads/ModBook/modStylesGlobal.css");

        // Append to TinyMCE head
        editor.dom.doc.head.appendChild(cssLink);
    });
});

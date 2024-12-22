// Listen to the BookStack setup TinyMCE editor event to run custom actions against the editor instance
window.addEventListener('editor-tinymce::setup', event => {
    // Gain a reference to the TinyMCE editor instance
    const editor = event.detail.editor;

    // An object that contains all abbreviations with the respective HTML for the icons
    const complimentEmojis = {
        "(y)": `<label class="like complimentIcon" contenteditable="false" title="Liked by {author}" alt="Liked by {author}"></label>`,
        "<3": `<label class="heart complimentIcon" contenteditable="false" title="{author} loves this" alt="{author} loves this"></label>`,
        "(wow)": `<label class="wow complimentIcon" contenteditable="false" title="{author} says wow!" alt="{author} says wow!"></label>`
    }

    // Wait for the init event
    editor.on('init', function () {
        // Predefine variables
        let author;
        let iconData;
        keyHistory = [];

        // Add an event listener for keydown
        editor.dom.doc.addEventListener("keydown", function (e) {
            // If the pressed key is backspace
            if (e.key === "Backspace") {
                // Check if the history exists
                if (keyHistory.length > 0) {
                    // When it exists, remove the last character from the array
                    keyHistory.pop();
                }
            }

            // When the pressed key is in name, longer than 1 character, ignore input
            if (e.key.length > 1)
                return;

            // Add the pressed key to the key history
            keyHistory.push(e.key);

            // If the array is larger than 10 items
            if (keyHistory.length > 10) {
                // Remove the first element in the array
                keyHistory.shift();
            }

            // Creates the deleteRangeAtCursor function
            const deleteRangeAtCursor = (editor, length) => {
                const editorRange = editor.selection.getRng();
                const node = editorRange.commonAncestorContainer;
                const range = document.createRange();
                range.selectNodeContents(node);
                range.setStart(node, editorRange.endOffset - length + 1);
                range.setEnd(node, editorRange.endOffset);
                range.deleteContents();
                editor.focus();
            }

            // Obtain an array of all keys from the complimentEmojis variable, then run an forEach loop
            Object.keys(complimentEmojis).forEach((text) => {
                // If the key history contains one of the complimentEmojis keys
                if (keyHistory.join("").includes(text)) {
                    // Clear key history
                    keyHistory = [];

                    // Obtain the authors name from the page
                    author = document.getElementsByClassName("name")[0].textContent;

                    // Replaces {author} with the author name
                    iconData = complimentEmojis[text].replaceAll("{author}", author);

                    // Prevents the last typed character from being sent into the editor
                    e.preventDefault();

                    // Deletes the abbreviation from the cursor position
                    deleteRangeAtCursor(editor, text.length);

                    // Inserts the HTML containing the icon data
                    editor.insertContent(iconData);

                    // Inserts a space after the icon
                    editor.insertContent(" ");
                }
            });
        });
    });
});

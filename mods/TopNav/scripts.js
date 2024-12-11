function tagToTop() {
    const tagSection = document.getElementById("tagbox");
    const leftContents = document.getElementsByClassName("tri-layout-left-contents")[0].getElementsByTagName("section");

    Array.from(leftContents).forEach(section => {
        tagSection.appendChild(section);
    });
}

function navToTop() {
    let pageNav;
    const bookNav = document.getElementById("book-tree").outerHTML;

    document.getElementById("book-tree").remove()
    tagSection = document.createElement("section");
    tagSection.id = "tagbox";

    if (document.getElementById("page-navigation")) {
        pageNav = document.getElementById("page-navigation").outerHTML;
        document.getElementById("page-navigation").remove()
        document.getElementsByTagName("main")[0].innerHTML = tagSection.outerHTML + "<div id='navhack'>" + bookNav + pageNav + "</div>" + document.getElementsByTagName("main")[0].innerHTML
    } else {
        document.getElementsByTagName("main")[0].innerHTML = tagSection.outerHTML + "<div id='navhack'>" + bookNav + "</div>" + document.getElementsByTagName("main")[0].innerHTML
    }

    tagToTop();
}

waitFor(".book-tree", navToTop);

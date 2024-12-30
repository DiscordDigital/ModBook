function mobileSearchBar() {
    const normalNav = document.getElementsByClassName("search-box")[0].parentElement;
    normalNav.classList.add("mobileNavSearch");
    normalNav.classList.remove("hide-under-l");

    if (document.getElementsByClassName("logo-image")[0]) {
        if (!document.getElementsByClassName("logo-image")[0].classList.contains("none")) {
            normalNav.classList.add("mobileNavSearchWithLogo");
        }
    }
}

waitFor(".mobile-menu-toggle", mobileSearchBar);

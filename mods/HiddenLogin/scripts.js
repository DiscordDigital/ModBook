window.onload = function () {
    document.body.addEventListener("keydown", (event) => {
        if (event.altKey && event.keyCode == 76) {
            document.location = '/login'
        }
    })
}

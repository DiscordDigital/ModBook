const waitFor = (search, callback, timeout = 2000, options = { childList: true, subtree: true }) => {
    if (typeof search !== 'string' || typeof callback !== 'function') {
        throw new Error('Invalid parameters. Expected string for search and function for callback.');
    }

    const observerAction = (observer, addedNode, observerTimeout) => {
        callback(addedNode);
        observer.disconnect();
        clearTimeout(observerTimeout);
    };

    const observer = new MutationObserver(function (mutationsList, observer) {
        mutationsList.forEach(function (mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function (addedNode) {
                    if (search.startsWith(".")) {
                        if (addedNode.classList && addedNode.classList.contains(search.substring(1))) {
                            observerAction(observer, addedNode, observerTimeout);
                        }
                    } else if (search.startsWith("#")) {
                        if (addedNode.id && addedNode.id === search.substring(1)) {
                            observerAction(observer, addedNode, observerTimeout);
                        }
                    } else {
                        if (addedNode.innerText && addedNode.innerText == search) {
                            observerAction(observer, addedNode, observerTimeout);
                        }
                    }
                });
            }
        });
    });

    const observerTimeout = setTimeout(() => {
        observer.disconnect();
    }, timeout);

    observer.observe(document.documentElement, options);
}

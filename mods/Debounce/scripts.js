// Standard debounce function
const debounce = (f, w = 50) => {
    // Initialize timeout variable in scope
    let t;

    // Return a function that takes a rest parameter
    return (...a) => {
        // Clear timeout each time the returned function is called
        clearTimeout(t);

        // Set a new timeout to execute function with arguments collected using delay
        t = setTimeout(() => f.apply(this, a), w);
    }
}

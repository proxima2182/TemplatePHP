function onWindowResizeDefault() {
    let wrap = document.getElementById('wrap');
    let footer = document.getElementById('footer');
    if (wrap && footer) {
        wrap.style.paddingBottom = `${footer.clientHeight}px`;
        wrap.style.minHeight = `calc(100vh - ${footer.clientHeight}px)`;
    }
}
window.addEventListener("DOMContentLoaded", function(){
    onWindowResizeDefault()
    // Handler when the DOM is fully loaded

});
addEventListener("resize", (event) => {
    onWindowResizeDefault();
});

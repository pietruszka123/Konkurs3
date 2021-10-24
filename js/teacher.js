$(".HorizontalNavBar > ol").children("li").each(function(i, e) {
    $(this).click(function(e) {
        if (this.id == "messages") return;
        $("iframe")[0].src = `${this.id}.php/headless`
    })
})
window.addEventListener("message", function(event) {
    console.log(event.data)
        //$("iframe")[0].contentWindow.postMessage("no czesc")
})
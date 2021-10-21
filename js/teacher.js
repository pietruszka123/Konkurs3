$(".HorizontalNavBar > ol").children("li").each(function(i,e){
    $(this).click(function(e){
        $("iframe")[0].src = `views/${this.id}.php`
    })
})
window.addEventListener("message", function (event) {
    console.log(event.data)
    $("iframe")[0].contentWindow.postMessage("no czesc")
})
window.top.postMessage("czesc")
window.addEventListener("message", function (event) {
    console.log(event.data)
})
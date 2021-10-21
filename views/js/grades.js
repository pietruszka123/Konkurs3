window.top.postMessage("czesc")
window.addEventListener("message", function (event) {
    console.log(event.data)
})
$("[id=gradeI]").each(function(i,e){
    $(this).on("click",function (e) { 
        console.log("?")
        console.log(this)    
    });
    $(this).keypress(function (e) { 
        
    });
})
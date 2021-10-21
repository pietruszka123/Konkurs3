window.top.postMessage("czesc")
window.addEventListener("message", function (event) {
    console.log(event.data)
})
function getPosY(pos,max,i){
    var a = pos
    var users = document.getElementById("users");
    a =  a.replace("i","")
    var a = Math.round((((a/max)+i)*max))
    if(a < 0 || a > users*max ){
        console.log(pos.replace("i",""))
        return pos.replace("i","");

    }
    return a
}
$("[class=ocenaI]").each(function(i,e){
    $(this).on("click",function (e) { 
        console.log("?")
        console.log(this)    
    });
    $(this).keydown(function (e) { 
        var max = document.getElementById("max").innerText;
        switch (e.keyCode) {
            case 40:
                
                $(`#i${getPosY(this.id,max,1)}`)[0].focus();
                e.stopPropagation()
                break;
        
            case 38:
                $(`#i${getPosY(this.id,max,-1)}`)[0].focus()
                e.stopPropagation()
                break;
            case 13:
                $(`#i${getPosY(this.id,max,1)}`)[0].focus();
                e.stopPropagation()
                break
        }
    });
})
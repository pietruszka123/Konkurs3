function sendMessage(Content, title, Receiver) {
    $.ajax({
        type: "POST",
        url: "/api.php/sendMessage",
        data: JSON.stringify({ Content: Content, title: title, Receiver: Receiver }),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            console.log(response)
            document.getElementById("titleInputBox").value = "Succes";
            document.getElementById("messageInputContent").value = "";
        },
        error: function (jqXHR, exception) {
            console.log(exception)
            console.log(jqXHR)
        }
    });
}
function Empty(v){
    return (v.trim().length != 0)? true : false;
}
document.getElementsByClassName("sendMessageButton")[0].addEventListener("click", (e) => {
    console.log("?")
    var title = document.getElementById("titleInputBox").value;
    var message = document.getElementById("messageInputContent");
    var Rec = document.getElementById("selectReceiver").value;
    if(Empty(title) || Empty(message.value) || Empty(Rec)){
        sendMessage(message.value,title,Rec);
    }else message.value = "error";
})
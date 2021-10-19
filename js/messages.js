function sendMessage(Content, title, Receiver) {
    $.ajax({
        type: "POST",
        url: "api.php/sendMessage",
        data: JSON.stringify({ Content: Content, title: title, Receiver: Receiver }),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            console.log(response)
            document.getElementById("titleInputBox").value = "Succes";
            document.getElementById("messageInputContent").value = "";
            Refresh();
        },
        error: function (jqXHR, exception) {
            console.log(exception)
            console.log(jqXHR)
        }
    });
}
function Empty(v) {
    return (v.trim().length != 0) ? true : false;
}
document.getElementsByClassName("sendMessageButton")[0].addEventListener("click", (e) => {
    console.log("?")
    var title = document.getElementById("titleInputBox").value;
    var message = document.getElementById("messageInputContent");
    var Rec = document.getElementById("selectReceiver").value;
    if (Empty(title) || Empty(message.value) || Empty(Rec)) {
        sendMessage(message.value, title, Rec);
    } else message.value = "error";
})
function initButtons() {
    $(".messagesButtons").children("button").each(function (index, element) {
        $(this).click((e) => {
            $(".messageContent").text("Ładowanie...");
            $.ajax({
                type: "post",
                url: "api.php/getMessageData",
                data: JSON.stringify({ messageId: parseInt(this.id) }),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (response) {
                    console.log(response)
                    $(".messageContent").html(response.message[0]);
                    
                }
            });
        })
    });
}
function Refresh() {
    $.ajax({
        type: "post",
        url: "/api.php/getMessagesElements",
        contentType: "application/json; charset=utf-8",
        success: function (response) {
            console.log(response)
            $(".messagesButtons").empty();
            for (let i = 0; i < response.message.length; i++) {
                var temp = $(response.message[i])
                    .hide()
                    .fadeIn(200);
                $(".messagesButtons").append(temp)
            }
            initButtons();
        }
    });
}
$("#refresh").click(function (e) {
    Refresh();
});
initButtons();
$("#Switch").click((e)=>{
    $(".sendMessageBox").toggle("fast");
})
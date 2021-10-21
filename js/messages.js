var Receivers = []
function sendMessage(Content, title, Receiver) {
    $.ajax({
        type: "POST",
        url: "api.php/sendMessage",
        data: JSON.stringify({ Content: Content, title: title, Receivers: Receiver }),
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
    if (Empty(title) || Empty(message.value) || Rec.length > 0) {
        sendMessage(message.value, title, Receivers);
        console.log(Receivers)
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
        url: "api.php/getMessagesElements",
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
$("#selectReciver").click(function(e){
   
})
function initRec(){
    $("Selected").empty();
    $(".notSelected").children("div").each(function(i,e){
        console.log(this)
        this.HiddenId = this.id;
        $(this).click(function(e){
            var element = $(this).detach();
            if(!$(this).hasClass("selected")){
                $('.Selected').append(element);
            }else{
                $('.notSelected').append(element);
            }
            element.toggleClass( "selected" );
        })
    })
}
$("#selectReceiverType").on("change",function(e){
    console.log(this.value)
})
function getSelected(){
    var t = []
    $(".Selected").children("div").each(function(i,e){
        t.push(this.HiddenId)
    })
    console.log(t)
    return t;
}
$("#subbmit").click(function(e){
    console.log($(".Selected").children("div").length)
    if($(".Selected").children("div").length == 0){
        alert("wybierz przynajmniej jeden samochód")
    }else{
        Receivers = getSelected();
        console.log(Receivers)
    }
})
initRec()
window.top.postMessage("czesc");
window.addEventListener("message", function (event) {
  console.log(event.data);
});
function getPosY(pos, max, i, users) {
  var a = pos;
  a = a.replace("i", "");
  var a = Math.round((a / max + i) * max);
  if (a < 0 || a > users * max-1) {
    return undefined;
  }
  return a;
}
var Grades;
function getGrades(){
    return new Promise((resolve,reject)=>{
        var arr = {"ClassId":1,"Users":[]};
        var GradesData = []
        $("tbody").children("tr").each(function (i, e) {
            if (i == 0){ 
                $(this).children("td").each(function (ii, ee) {
                    GradesData.push({"weight":0,"desc":$(this).text()});
                })
                return;
            }
            console.log()
            var temp = {"id":$(this).attr("data-id"),"grades":[],};
            $(this).children("td").each(function (ii, ee) {
                if (ii == 0) return;
                temp.grades.push({"GradesData":GradesData[ii],"edited":false,"value":$(this).find('input').val(),"empty":($(this).find('input').attr("data-empty")) ? true : false });
              });
            arr.Users.push(temp);
          }).promise().done(function () {
              resolve(arr);
          })
    })
}
function SendUpdateGrades(){
    var COPY = $.extend(true,{},Grades)
    console.log(COPY.Users.length)
    for (let i = 0; i < COPY.Users.length; i++) {
        console.log("-------------")
        var copy =  COPY.Users[i].grades.slice();
        for (let j = 0; j < COPY.Users[i].grades.length; j++) {
            if(!COPY.Users[i].grades[j].edited){
                console.log("??")
                console.log(copy.indexOf(COPY.Users[i].grades[j]))
                copy.splice(copy.indexOf(COPY.Users[i].grades[j]), 1)
                console.log(copy)
            }
        }
        COPY.Users[i].grades = copy
    }
    var copy = COPY.Users.slice()
    for (let i = 0; i < COPY.Users.length; i++) {
        console.log(`------------\n${COPY.Users[i].grades.length == 0}`)
        if(COPY.Users[i].grades.length == 0){
            console.log(copy)
            copy.splice(copy.indexOf(COPY.Users[i]),1);
        }else{
            console.log("?")
        }
    }
    console.log(copy)
    console.log(COPY)
    if(copy.length == 0){
        console.log("tak")
        return
    }
    COPY.Users = copy 

    $.ajax({
        type: "Post",
        url: "/api.php/UpdateGrades",//nie zmienie tego
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify(COPY),
        success: function (response) {
            console.log(response);
        },
        error: function(e,r){
            $("body").html(`źle ${JSON.stringify(e)}  ${r}`)
        }
    });
}
var max,users
getGrades().then((arr)=>{
    console.log(arr);
    Grades = arr;
    var COPY = $.extend(true,{},arr)
    max = arr.Users[0].grades.length || 0;
    users = arr.Users.length;
    console.log(max)
    console.log(users)
    $("[class=ocenaI]").each(function (i, e) {
      $(this).on("input", function (e) {
        console.log(COPY);
        Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value = this.value
        if(Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value == COPY.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value){
            Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].edited = false
            console.log("?")
        }else{
            Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].edited = true
        }
        console.log(Grades)
      });
      $(this).keydown(function (e) {
        switch (e.keyCode) {
          case 40:
            var g = getPosY(this.id, max, 1, users);
            if (g) {
              $(`#i${g}`)[0].focus();
            }
            e.stopPropagation();
            break;
          case 38:
            var g = getPosY(this.id, max, -1, users);
            if (g) {
              $(`#i${g}`)[0].focus();
            }
            e.stopPropagation();
            break;
          case 13:
            var g = getPosY(this.id, max, 1, users);
            if (g) {
              $(`#i${g}`)[0].focus();
            }
            e.stopPropagation();
            break;
        }
      });
    });
    $("#saveGrads").click(function (e) { 
        SendUpdateGrades();
        e.preventDefault();
    });
});

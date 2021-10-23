window.top.postMessage("czesc");
window.addEventListener("message", function (event) {
  console.log(event.data);
});
function getPosY(pos, max, i, users) {
  var a = pos;
  a = a.replace("i", "");
  var a = Math.round((a / max + i) * max);
  if (a < 0 || a > users * max) {
    return undefined;
  }
  return a;
}
function getGrades(){
    return new Promise((resolve,reject)=>{
        var arr = [];
        $("tbody").children("tr").each(function (i, e) {
            if (i == 0) return;
            var temp = [];
            $(this).children("td").each(function (ii, ee) {
                if (ii == 0) return;
                temp.push($(this).find('input').val());
              });
            arr.push(temp);
          }).promise().done(function () {
              resolve(arr);
          })
    })
}

getGrades().then((arr)=>{
    console.log(arr);
    var max = arr[0].length || 0;
    var users = arr.length;
    $("[class=ocenaI]").each(function (i, e) {
      $(this).on("click", function (e) {
        console.log("?");
        console.log(this);
      });
      $(this).keydown(function (e) {
        switch (e.keyCode) {
          case 40:
            var g = getPosY(this.id, max, 1, users);
            console.log(g)
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
});

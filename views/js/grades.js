window.top.postMessage("czesc");
window.addEventListener("message", function(event) {
    console.log(event.data);
});

function getPosY(pos, max, i, users) {
    var a = pos;
    a = a.replace("i", "");
    var a = Math.round((a / max + i) * max);
    if (a < 0 || a > users * max - 1) {
        return undefined;
    }
    return a;
}
var Grades, ECOPY;

function initLabels() {
    $("tbody").children("tr").each(function(i, e) {
        if (i == 0) {
            $(this).children("td").each(function(ii, ee) {
                var desc = $(this).find("#Desc")
                var weight = $(this).find("#Weight")
                if (desc == undefined || weight == undefined) return
                var id = $(this).attr("id")
                desc.on("input",
                    function(e) {
                        Grades.labels[id - 1].desc = $(this).val();
                        Grades.labels[id - 1].edited = true;
                    })
                weight.on("input", function(e) {
                    Grades.labels[id - 1].weight = $(this).val();
                    Grades.labels[id - 1].edited = true;
                })
            })
        }
    })
}

function getGrades() {
    return new Promise((resolve, reject) => {
        var arr = { "labels": [], "ClassId": $("#wyborKlasy").val(), "SubjectId": $("#wyborPrzedmiotu").val(), "Users": [] };
        var GradesData = []
        $("tbody").children("tr").each(function(i, e) {
            if (i == 0) {
                $(this).children("td").each(function(ii, ee) {
                    var desc = $(this).find("#Desc")
                    var weight = $(this).find("#Weight")
                    if ($(this).attr("data-id")) {
                        arr.labels.push({ "id": $(this).attr("data-id"), "desc": desc.val(), "weight": weight.val() })
                    }
                    GradesData.push({ "id": $(this).attr("data-id"), "edited": false, "empty": false, "weight": weight.val(), "desc": desc.val() });
                })
                return;
            }
            var temp = { "id": $(this).attr("data-id"), "grades": [], };
            $(this).children("td").each(function(ii, ee) {
                if (ii == 0) return;
                temp.grades.push({ "GradesData": GradesData[ii], "edited": false, "value": $(this).find('input').val(), "empty": ($(this).find('input').attr("data-empty")) ? true : false, "id": ($(this).find('input').attr("data-id")) ? $(this).find('input').attr("data-id") : -1 });
            });
            arr.Users.push(temp);
        }).promise().done(function() {
            resolve(arr);
            console.log(arr)
        })
    })
}

function SendUpdateGrades() {
    var COPY = $.extend(true, {}, Grades)
    console.log(JSON.stringify(COPY))
    for (let i = 0; i < COPY.Users.length; i++) {
        var copy = COPY.Users[i].grades.slice();
        for (let j = 0; j < COPY.Users[i].grades.length; j++) {
            if (!COPY.Users[i].grades[j].edited) {
                copy.splice(copy.indexOf(COPY.Users[i].grades[j]), 1)
            }
        }
        COPY.Users[i].grades = copy
    }
    console.log(COPY)
    var copy = COPY.Users.slice()
    for (let i = 0; i < COPY.Users.length; i++) {
        if (COPY.Users[i].grades.length == 0) {
            copy.splice(copy.indexOf(COPY.Users[i]), 1);
        }
    }
    if (copy.length == 0) {
        return
    }
    COPY.Users = copy

    $.ajax({
        type: "Post",
        url: "/Konkurs3/api.php/UpdateGrades", //nie zmienie tego
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify(COPY),
        success: function(response) {
            console.log(response);
        },
        error: function(e, r) {
            $("body").html(`źle ${JSON.stringify(e)}  ${r}`)
        }
    });
}
var max, users

function initInputs(COPY) {
    $("[class=ocenaI]").each(function(i, e) {
        $(this).on("input", function(e) {
            console.log(this.value)
            console.log(Grades)
            console.log($(this).parent().parent().attr("id").replace("t", ""))
            Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value = this.value
            console.log(COPY.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value)
            console.log(this.value)
            if (Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value == COPY.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].value) {
                Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].edited = false
            } else {
                Grades.Users[$(this).parent().parent().attr("id").replace("t", "")].grades[this.id.replace("i", "")].edited = true
            }
        });
        $(this).keydown(function(e) {
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
}
getGrades().then((arr) => {
    if (arr.Users.length == 0) return;
    Grades = arr;
    ECOPY = $.extend(true, {}, arr)
    max = arr.Users[0].grades.length || 0;
    users = arr.Users.length;
    initInputs(ECOPY)
    initLabels();
    $("#saveGrads").click(function(e) {
        SendUpdateGrades();
        e.preventDefault();
    });
});
$("#addLabel").click(function(e) {
    var index = 0;
    max++;
    $.ajax({
        type: "Post",
        url: "/Konkurs3/api.php/AddLabel", //nie zmienie tego
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(response) {
            response
        }
    });
    $("tbody").children("tr").each(function(i, e) {
        var l = $("tbody").children("tr").length
        if (i == 0) {
            var ll = $(this).children("td").length
            $(this).children("td").each(function(ii, ee) {
                if (ii == ll - 1) {
                    console.log("?")
                    var t = $(`<td class="GradeDesc"><input autocomplete="off" type="text" id="Desc"> <input autocomplete="off" type="text" id="Weight"></td>`)
                    $(t).insertBefore($(this))
                    console.log(t.find("Desc"))
                    console.log(t.find("Weight"))
                } else {
                    $(this).find("Desc").off("input")
                    $(this).find("Weight").off("input")
                }
            })
        } else
            $(this).children(".Grade").each(function(ii, ee) {
                $(this).find("input").attr("id", `i${index}`);
                $(this).find("input").off("keydown")
                $(this).find("input").off("input")
                index++;
            }).promise().done(function() {
                $(e).append(`<td class="Grade"><input id='i${index}' autocomplete="off" class="ocenaI" type="text"></td>`)
                Grades.Users[i - 1].grades.push({ "GradesData": { "weight": 0, "desc": "" }, "edited": false, "value": "", "empty": true, "id": ($(this).find('input').attr("data-id")) ? 1 : 2 })
                ECOPY.Users[i - 1].grades.push({ "GradesData": { "weight": 0, "desc": "" }, "edited": false, "value": "", "empty": true, "id": ($(this).find('input').attr("data-id")) ? 1 : 2 })
                index++;
            })
    })
    initInputs(ECOPY)
    Grades.labels.push({ "id": max, "desc": "", "weight": 0, "edited": false, "empty": true });
    initLabels();
})
$(".A").prepend($("#AttendenceDate").detach().attr("id", "AttendenceDateS"));
$(".T").prepend($("#TDate").detach().attr("id", "TDateS"));

function changeTimeTable(i) {
    $.ajax({
        type: "post",
        url: "api.php/getTimeTable",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({ direction: i }),
        success: function(response) {
            $(".TimeTableE").html(response.message);
            $("#TDateS").remove();
            var date = $("#TDate").detach();
            date.attr("id", "TDateS")
            $(".T").prepend(date);
        },
        error: function(e, i) {
            $(".TimeTableE").html("UwU, somethin went wong.");
        }
    })
}

function changeAttendance(i) {
    $.ajax({
        type: "post",
        url: "api.php/getAttendance",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({ direction: i }),
        success: function(response) {
            $(".attendanceTableE").html(response.message);

            $("#AttendenceDateS").remove();
            var date = $("#AttendenceDate").detach();
            date.attr("id", "AttendenceDateS")
            $(".A").prepend(date);
        },
        error: function(e, i) {
            $(".attendanceTableE").html("UwU, somethin went wong.");
        }
    })
}
$("#backward").click(function(e) {
    changeTimeTable(-1);
})
$("#reset").click(function(e) {
    changeTimeTable(0);
})
$("#forward").click(function(e) {
    changeTimeTable(1);
})
$("#back").click(function(e) {
    changeAttendance(-1);
})
$("#res").click(function(e) {
    changeAttendance(0);
})
$("#for").click(function(e) {
    changeAttendance(1);
})
$.ajax({
    type: "post",
    url: "api.php/getEndTime",
    contentType: "application/json; charset=utf-8",
    success: function(response) {
        var endDate = new Date(response.message * 1000);
        setInterval(() => {
            const today = new Date();
            const days = parseInt((endDate - today) / (1000 * 60 * 60 * 24));
            const hours = parseInt(Math.abs(endDate - today) / (1000 * 60 * 60) % 24);
            const minutes = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000 * 60) % 60);
            const seconds = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000) % 60);

            $(".daysUntilEndOfYear").html(`<h3 style="font-family: 'Poppins', sans-serif;"> Do końca roku szkolnego zostało:</h3><p style="font-family: 'Poppins', sans-serif;">${days}
             dni</p><p style="font-family: 'Poppins', sans-serif;">
            ${hours + 24 * days}
             godziny</p><p style="font-family: 'Poppins', sans-serif;">
            ${minutes + hours * 60 + 60 * 24 * days}
             minut</p><p style="font-family: 'Poppins', sans-serif;">
            ${seconds + 60 * minutes + 60 * 60 * hours + 60 * 60 * 24 * days}
             sekund</p>`)
                //`<>DNI DO KONCA ROKU: ${days}<br>GODZINY DO KONCA ROKU: ${hours + 24 * days}<br>;MINUTY DO KONCA ROKU: ${minutes + hours * 60 + 60 * 24 * days}<br>;SEKUNDY DO KONCA ROKU: ${seconds + 60 * minutes + 60 * 60 * hours + 60 * 60 * 24 * days}<br>`)
        }, 1000)
    }
});
$(".singleGrade").each(function(i, e) {
    $(this).on("mouseover", function(e) {
        var p = $($(this).find(".gradeGreaterInfo")[0]).offset()
        var el = $($(this).find(".gradeGreaterInfo")[0]).clone();
        el.css({
            position: "absolute",
            marginLeft: 0,
            marginTop: 0,
            top: p.top,
            left: p.left,
            visibility: "visible",
            opacity: "100%",
        }).appendTo('body');
        this.comment = el;
    })
    $(this).on("mouseout", function(e) {
        $(this.comment).remove();
    })
})
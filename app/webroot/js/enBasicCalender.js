//Json Example http://kamisama.github.io/cal-heatmap/datas-years.json
window.onload = function(){

    var cal = new CalHeatMap();
    cal.init({
        data: "./tryNumJson/basic",
        dataType: "json",
        start: new Date(2015, 2),
        domain: "month",
        subDomain: "day",
        range: 12 //何個表示するか

    });
}
$(function() {
    //$(".main").css("text-align","center");
    $(".main").css("width", "100%");
    $(".main").css("border", "1px solid #000");

    $("#dates").css("color","red");
    $("div .main").css("width", "80%");
    $("div .main").css("border", "1px solid #FF0000");
    $("div .main").css("margin", "0 auto");

});



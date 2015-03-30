//Json Example http://kamisama.github.io/cal-heatmap/datas-years.json
window.onload = function(){

    var cal = new CalHeatMap();
    cal.init({
        data: "http://duoprac/EnResults/tryNumJson/basic",
        dataType: "json",
        start: new Date(2015, 2),
        domain: "month",
        subDomain: "day",
        range: 12 //何個表示するか

    });
}

jQuery(function ($) {
    

});
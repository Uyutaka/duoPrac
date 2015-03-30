window.onload = function(){

    var datas = [
        {date: 946702811, value: 15},
        {date: 946702812, value: 25},
        {date: 1427706317, value: 100},
        {date: 946702813, value: 10},
        {date: 1427339834, value: 100}
    ]
    console.log(datas);


    var parser = function(data) {
        var stats = {};
        for (var d in data) {
            stats[data[d].date] = data[d].value;
        }
        return stats;
    };

// ページ読み込み時に実行したい処理
    var cal = new CalHeatMap();
    cal.init({
        data: datas,
        start: new Date(2015, 2),
        domain: "month",
        subDomain: "day",
        range: 12, //何個表示するか
        scale: [40, 60, 80, 100],
        afterLoadData: parser

    });
}
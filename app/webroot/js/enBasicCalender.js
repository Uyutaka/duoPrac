
window.onload = function(){
// ページ読み込み時に実行したい処理
    var cal = new CalHeatMap();
    cal.init({
        data: "datas.json",
        start: new Date(2015, 2),
        domain: "month",
        subDomain: "day",
        range: 12, //何個表示するか
        scale: [40, 60, 80, 100]

    });
}
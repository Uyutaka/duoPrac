/**
 * Created by Yutaka on 15/04/16.
 */



var xmlHttpRequest = new XMLHttpRequest();
xmlHttpRequest.onreadystatechange = function()
{
    if( this.readyState == 4 && this.status == 200 )
    {
        if( this.response )
        {
            console.log(this.response);
            var graphDate = this.response;
            console.log(graphDate);
            var chart = AmCharts.makeChart("chartdiv", {
                "type": "serial",
                "theme": "light",
                "marginRight": 70,
                "pathToImages": "http://www.amcharts.com/lib/3/images/",
                "dataDateFormat": "YYYY-MM-DD",
                "valueAxes": [{
                    "id": "v1",
                    "axisAlpha": 0,
                    "position": "left"
                }],
                "graphs": [{
                    "id": "g1",
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "bulletSize": 5,
                    "hideBulletsCount": 50,
                    "lineThickness": 2,
                    "title": "red line",
                    "useLineColorForBulletBorder": true,
                    "valueField": "value"
                }],
                "chartScrollbar": {
                    "graph": "g1",
                    "scrollbarHeight": 30,
                    "backgroundAlpha": 0,
                    "selectedBackgroundAlpha": 0.5,
                    "graphFillAlpha": 0,
                    "graphLineAlpha": 0.5,
                    "selectedGraphFillAlpha": 0,
                    "selectedGraphLineAlpha": 1
                },
                "chartCursor": {
                    "cursorPosition": "mouse",
                    "pan": true,
                    "valueLineEnabled": true,
                    "valueLineBalloonEnabled": true
                },
                "categoryField": "date",
                "categoryAxis": {
                    "parseDates": true,
                    "dashLength": 1,
                    "minorGridEnabled": true,
                    "position": "top"
                },
                "export": {
                    "enabled": true,
                    "libs": {
                        "path": "http://www.amcharts.com/lib/3/plugins/export/libs/"
                    }
                },
                "dataProvider": graphDate
            });

            chart.addListener("rendered", zoomChart);

            zoomChart();

            function zoomChart() {
                chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
            }
        }
    }
}

xmlHttpRequest.open( 'GET', './trySumJson/basic', true );
xmlHttpRequest.responseType = 'json';
xmlHttpRequest.send( null );





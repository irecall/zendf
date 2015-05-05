//Flot Line Chart
$(document).ready(function() {
    console.log("document ready");
    var offset = 0;
    plot();

    function plot() {
        var data = [['Verwerkende industrie', 9],
		            ['Retail', 8], 
		            ['Primaire producent', 7],
		            ['Out of home', 6],
		            ['Groothandel', 5],   
		            ['Grondstof', 4],
		            ['Consument', 3], 
		            ['Bewerkende industrie', 2]];

        console.log(data);
        var options = {

            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            grid: {
                hoverable: true //IMPORTANT! this is needed for tooltip to work
            },
            yaxis: {
                min: 0,
                max: 10000
            },
            
            xaxis: { 
                renderer:$.jqplot.DateAxisRenderer, 
                tickInterval: '5 days',//x 轴间隔的时间 
                tickOptions:{formatString:'%y/%m/%d'}, 
            
            } 
        };

        var plotObj = $.plot($("#flot-line-chart"), [{
                data: data,
                label: "tool"
            }],
            options);
    }
});

//$(document).ready(function(){   
//    jQuery.jqplot.config.enablePlugins = true;  
//    var data = [
//		            ['Verwerkende industrie', 9],
//		            ['Retail', 8], 
//		            ['Primaire producent', 7],
//		            ['Out of home', 6],
//		            ['Groothandel', 5],   
//		            ['Grondstof', 4],
//		            ['Consument', 3], 
//		            ['Bewerkende industrie', 2]
//                ];  
//plot7 = jQuery.jqplot('chart',[data],  
//      {  
//        title: ' ',  
//        seriesDefaults: {
//        	shadow: true, 
//        	renderer: jQuery.jqplot.PieRenderer, 
//        	rendererOptions: { 
//        		showDataLabels: true 
//        		} 
//        },  
//        legend: { show:true }  
//      }  
//    );           
//
//});   
(function($){

    $('.input-daterange').datepicker({
            todayBtn: true,
            todayHighlight: true,
            format:'yyyy-mm-dd',
        });
    
//    $(document).ready(function(){
//    	var data = [
//{"date":"2014-11-17","caigou":"2000"},
//{"date":"2014-11-24","caigou":"234"},
//{"date":"2014-11-26","caigou":"234","dingcan":"20"},
//{"date":"2014-11-25","dingcan":"234"}
//];
//
//    	  		// 產生圖表, 資料來源為 data
//    	  		// x 軸為 period, y 軸為 ['licensed', 'sorned']
//    	  		Morris.Line({
//    	  		  "element": "graph",
//    	  		  "data": data, 
//    	  		  "xkey": "date",
//    	  		  "ykeys": ['caigou','dingcan'],
//    	  		  "labels":['caigou','dingcan'],
//    	  		});
//    })
//     {	"element":"graph",
//    	"data":[
//    	        
//    	        ],
//    	"xkey":"date",
//    	"ymax":"auto 1000",
//    	"ykeys":["caigou","dingcan"],
//    	"lables":["caigou","dingcan"]
//    	
//    }
//    
   
    $('#viewReport').on('click',function(){
    	$catagory_id = $('#reportsCategory');
    	o = {
    			categories_id:$('#reportsCategory'),
    			start:$('#startTime'),
    			end:$('#endTime')
    	}
    	
    	d = {}
    	for(var i in o){
    		d[i] = o[i].val()
    	}
    	
    	if(d.categories_id<1){
    		alert('please select category');
    		o.categories_id.focus();
    	}else if(d.start.length<1){
    		alert('please select category');
    		o.start.focus();
    	}else if(d.end.length<1){
    		alert('please select category');
    		o.end.focus();
    	}else{
    		$.ajax({
    			url:'reports/search',
    			type:'POST',
    			data:d,
    			dataType:'JSON',
    			cache:false,
    			success:function(res){
    				$('#graph').html('');
    				console.log(res);
	    			Morris.Line(res);
    			}
    		});
    	}
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    })
})(jQuery)
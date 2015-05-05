function Queue(){

    //存储数据数组

    this.data = new Array();

    /*

    * @brief: 数据入队

    * @param: data数据列表

    * @return: 返回当前队列数据个数

    * @remark: 1.push方法参数可以多个

    *    2.参数为空时返回-1

    */

    Queue.prototype.push = function(vElement){

        if (arguments.length == 0)

            return - 1;

        //数据入队

        for (var i = 0; i < arguments.length; i++){

            this.data.push(arguments[i]);

        }

        return this.data.length;
    }

    /*

    * @brief: 数据出队

    * @return: data

    * @remark: 当队列数据为空时,返回null

    */

    Queue.prototype.pop = function(){

        if (this.data.length == 0)

            return null;

        else

            return this.data.shift();
 

    }

    /*

    * @brief: 获取队列数据个数

    * @return: 数据个数

    */

    Queue.prototype.count = function(){

        return this.data.length;

    }

    /*

    * @brief: 返回队头素值

    * @return: vElement

    * @remark: 若队列为空则返回null

    */

    Queue.prototype.GetHead = function(){

        if (this.data.length == 0)

            return null;

        else

            return this.data[0];

    }

    /*

    * @brief: 返回队尾素值

    * @return: data

    * @remark: 若队列为空则返回null

    */

    Queue.prototype.GetEnd = function(){

        if (this.data.length == 0)

            return null;

        else

            return this.data[this.data.length - 1];

    }

    /*

     * @brief: 设置队尾素值

     * @return: data

     * @remark: 若队列为空则返回null

     */

     Queue.prototype.SetEnd = function($data){

         if (this.data.length == 0)

             return null;

         else

             return this.data[this.data.length - 1]=$data;

     }
    /*

    * @brief: 将队列置空

    */

    Queue.prototype.MakeEmpty = function(){

        this.data.length = 0;

    }

    /*

    * @brief: 判断队列是否为空

    * @return: 队列为空返回true,否则返回false

    */

    Queue.prototype.IsEmpty = function(){

        if (this.data.length == 0)

            return true;

        else

            return false;

    }

    /*

    * @brief: 将队列数据转化为字符串

    * @return: 队列数据字符串

    */

    Queue.prototype.toString = function(){

        var sResult = (this.data.reverse()).toString();

        this.data.reverse()

        return sResult;

    }
    
    /*

     * @brief: 清空队列

     */
    
    Queue.prototype.clear = function(){
    	this.data = [];
    }
    /*

     * @brief: 返回倒数第二个队列

     */
}


var cloggerDropzone = $('cloggerDropzone')
// "myAwesomeDropzone" is the camelized version of the HTML element's ID
Dropzone.options.cloggerDropzone = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 2, // MB
  url:'file-upload',
  accept: function(file, done) {
	  alert();
    if (file.name == "justinbieber.jpg") {
      done("Naha, you don't.");
    }
    else { done(); }
  }
};


$('#reviewInvoiceDate').datepicker({
    todayBtn: true,
    calendarWeeks: true,
    todayHighlight: true,
    
}).on('click',function(){
	$('.datepicker').css('z-index',1060)
});
$('#manageInvoiceDate').datepicker({
    todayBtn: true,
    calendarWeeks: true,
    todayHighlight: true
    
});


$("#skipImagePath").load(function(){
	filterImage()
	});

function filterImage(now) {
	var ImgD = document.getElementById('skipImagePath');
	
	var backWidth = $('#warpImage').width();
	var backHeight = $('#warpImage').height();
    var image = new Image();
    image.src = ImgD.src;
    console.log(backHeight)
	if (image.width > backHeight) {
		console.log(backHeight)	
		ImgD.width = backHeight;
		
		
	}

	return true;
}


function rotateImageUI(obj,direction)
{
   var parents = obj.parents('.panel-body');

   var angle = (direction == "L")? angle = -90 : angle = +90;

   var rotation = getRotationDegrees(obj);
   var now = rotation+angle;
	
    obj.animate({  borderSpacing: now }, {
        step: function(now,fx) {
          $(this).css('-webkit-transform','rotate('+now+'deg)'); 
          $(this).css('-moz-transform','rotate('+now+'deg)');
          $(this).css('transform','rotate('+now+'deg)');
        },
        duration:'slow'
    },'linear');
    

}
function getRotationDegrees(obj) {
    var matrix = obj.css("-webkit-transform") ||
    obj.css("-moz-transform")    ||
    obj.css("-ms-transform")     ||
    obj.css("-o-transform")      ||
    obj.css("transform");
    if(matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
    } else { var angle = 0; }

    return angle;
}


  //图片旋转
$('#rotateRToolsButton').on( 'click', function() {
    rotateImageUI($('#invoicesImagePath'), 'R');
});
$('#rotateLToolsButton').on( 'click', function() {
    rotateImageUI($('#invoicesImagePath'), 'L');
});

$('#skipImageLeft').on( 'click', function() {
    rotateImageUI($('#skipImagePath'), 'L');
}).parent().offset().top;
$('#skipImageRight').on( 'click', function() {
    rotateImageUI($('#skipImagePath'), 'R');
});

(function($){

	//实例化一个缓存队列
	var invoQueue = new Queue;
	var historyQueue = new Queue;
    
	
	//获取一条数据 缓存中存在数据将会直接拿到缓存的数据
	function getInvoices(cacheObj,id){
		var data = cacheObj.data('invoices_id:'+id);
		if(!data){
			$.ajax({
				url:'invoices/getOneInvoicesData/'+id,
				dataType:'JSON',
				cache:false,
				success:function(res){
					if(res.status){
						$('#editReceipt').data('invoices_id:'+res.data.id,res.data);
						setEditInvoicesContent(res.data);
						console.log(res.data);
					}
				}
			});
		}else{
			setEditInvoicesContent(data);
		}
	}
	//创建公司下的Category选择框的属性
	function createCompanyCategorysList (data){
		console.log(data)
			var strTmp = '';					
			if(data.categorys.length>0){	
				//check category is exist					
				if(!data.category_id){
					strTmp += '<option selected value="0">Please Select Category</option>';
				}
				
				for(var i in data.categorys){
					var isSelectedCategory = data.categorys[i].id == data.categories_id ?'selected':'';    						
					strTmp += '<option value="'+data.categorys[i].id+'"'+isSelectedCategory+'>'+data.categorys[i].description+'</option>';
				}
			}else{
				strTmp = '<option value="0">Please Add Catagory</option>';
			}
			return strTmp;
	}
	//为发票数据编辑modal中添加数据
	function setEditInvoicesContent (data){				
		$('#editCategorys').html(createCompanyCategorysList(data));
		$('#invoicesImagePath').attr('src',data.imagePath);
		$('#reviewInvoicePurchaseDate').val(data.purchaseDate);
		$('#reviewInvoicesValue').val(data.value);
		$('#reviewInvoicesNumber').val(data.number);
		$('#reviewInvoicesDescription').val(data.description);    
		$('#reviewInvoicesId').val(data.id);  		
	}

	
	
	//创建公司下的Currency选择框的属性
	function createCompanyCurrencysList (data){
		var strTmp = '';					
		if(data.currencys.length>0){
								
			if(data.currencyData_id=='0'){

				strTmp += '<option selected value="0">Please Select Currency</option>';
			}
			for(var i in data.currencys){
				var isSelectedCategory = data.currencys[i].id == data.currencyData_id ?'selected':'';    						
				strTmp += '<option value="'+data.currencys[i].id+'"'+isSelectedCategory+'>'+data.currencys[i].currencyCode+'</option>';
			}
		}else{
			strTmp = '<option value="0">Please Add Currency</option>';
		}
		return strTmp;
	}
	
	//更新一条缓存
	function updateInvoicesCache (data){
		$('#editReceipt').removeData('invoices_id:'+data.id).data('invoices_id:'+data.id,data);
	}
	
	//更新缓存数据
	function updateInvoicesCacheAll(data){
		for(var i in data){
			//向缓存中增加一条数据
			updateInvoicesCache(data[i]);					
			//把invoices未编辑过的数据 存到缓存队列中
			if(data[i].skip == 0){
				invoQueue.push(data[i])
			}
			
		}
	}
	
	function updateInvoicesTable(data){
		var str = '';
		var category;
		str+='<tr class="gradeX even" id="tableID_{id}">';					
            str+='<td class="sorting_1" >{id}</td>';
            str+='<td class="center ">{purchaseDate}</td>';
        	str+='<td class="center ">{validatedDate}</td>';
            str+='<td class=" ">{category}</td>';
           	str+='<td class="center ">{value}</td>';
            str+='<td class="center ">{number}</td>';
            str+='<td class=" ">{descript}</td>';
            str+='<td class=" "><button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#editReceipt"><i class="fa fa-list"></i></button></td>';
    	str+='</tr>';            	
		for(var i in data.categorys){				
			if(data.categorys[i].id == data.categories_id){
				category = data.categorys[i].description;	
				break;
			}
		}
		str = str.replace(/{id}/g,data.id);				
		str = str.replace(/{purchaseDate}/,data.purchaseDate?data.purchaseDate:'');
		str = str.replace(/{validatedDate}/,data.validatedDate?data.validatedDate:'');
		str = str.replace(/{category}/,category?category:'');
		str = str.replace(/{value}/,data.value?data.value:'');
		str = str.replace(/{number}/,data.number?data.number:'');
		str = str.replace(/{descript}/,data.description?data.description:'');
		return str;			
	}
	
	//towards invoices Table add one data			
	function appendInvoicesTableOnce(data){
			$('#dataTables-example tbody').append(updateInvoicesTable(data));
	}
	
	//add table td
	function appendInvoicesTableAll(data){
		for(var i in data){
			appendInvoicesTableOnce(data[i]);
		}
	}

	function updateInvoicesSkipData(data){
		console.log(data);
		$('#skipInvoicesId').val(data.id);
		$('#skipImagePath').attr('src',data.imagePath);
		$('#skipSelectCompanys').html(createCompanyCategorysList(data));
		$('#skipSelectCurrency').html(createCompanyCurrencysList(data));
		$('#skipPurchaseDate').val(data.purchaseDate);	
		$('#skipValue').val(data.value);
		$('#skipNumber').val(data.number);
		$('#skipDescription').val(data.description);	
	}
	
	$.ajax({
		url:'/invoices/getInvoicesData',
		type:'GET',
		cache:false,
		dataType:'JSON',
		success:function($res){
			if($res.status){
				//invoices add data
				appendInvoicesTableAll($res.data);
				updateInvoicesCacheAll($res.data);
				
			    $(document).ready(function() {
			    	
			        $('#dataTables-example').dataTable();
			        //触发下一个发票，获取一个信息
			        
			        $('#manage .btn-warning').trigger('click');
			    });
			    
			}
		}
	})
	
	$('#dataTables-example').on('click','tbody button',function(){
		var id = $(this).parents('tr').find(':first').html();
		var eidtModal = $('#editReceipt');
		getInvoices(eidtModal,id);		
	});

	
	//skip next action
	
	$('#skipNextButton').on('click',function(){
		
		console.log('skip & next：'+invoQueue.count())
		if(invoQueue.count()>0){
			
			$("#skipImagePath").attr('src',"");
			$("#skipImagePath").attr('width',"");
			$("#skipImagePath").attr('height',"");
			$("#skipImagePath").removeAttr('style');
			var data = invoQueue.pop();
			updateInvoicesSkipData(data);
			invoQueue.push(data);
			historyQueue.push(data);
		}
		
		
	});
	//加载优先级问题，导致促发click事件时 函数没有被定义，继续寻找方法
	//.trigger('click');

	$('#skipHistory').unbind('click').on('click',function(){
		console.log('skip & next：'+invoQueue.count())
		var data = historyQueue.pop();
		if(data){
			updateInvoicesSkipData(data);
		}
	});
	//edit skip Receipt
    $('#saveNextButton').unbind('click').click(function (){
    	console.log('save & next：'+invoQueue.count())
    	var data = '';

    	var o = {
				id:$('#skipInvoicesId'),
				categories_id:$('#skipSelectCompanys'),
				currencyData_id:$('#skipSelectCurrency'),
				purchaseDate:$('#skipPurchaseDate'),	
				value:$('#skipValue'),
				number:$('#skipNumber'),
				description:$('#skipDescription'),																										
			}
		d = {};
		for(var i in o){
			d[i]=o[i].val()
		}
		//alert($.parseJSON(d));
		
		if(d.categories_id<1){
			alert('Please Select Category');
			o.categories_id.focus();
		}else if(d.value.length<1){
			alert('Please Input Value');
			o.value.focus();
		}else if(d.currencyData_id<1){
			alert('Please Input Currency');
			o.currencyData_id.focus();
		}else if(d.number.length<1){
			alert('Please Input number');
			o.number.focus();
		}else if(d.description.length<1){
			alert('Please Input Description');
			o.description.focus();
		}else if(d.id.length < 1){
			alert('Param Error,Please ')
			$('#editReceipt').modal('hide');
		}else if(d.purchaseDate.length<1){
			alert('Please Input purchaseDate');
			d.purchaseDate.focus();
		}else{
			$.ajax({
				url:'invoices/editInvoices',
				type:'POST',
				data:d,
				cache:false,
				dataType:'JSON',
				success:function(res){
					if(res.errno==0){
						data = invoQueue.pop();
						updateInvoicesSkipData(data);
					}else{
						invoQueue.push(data);
					}
				}
			});
		}
		
	});
	
	//edit Receipt
    $('#editReceipt').find('button:last').click(function (){
	    
    	var o = {
				id:$('#reviewInvoicesId'),
				categories_id:$('#editCategorys'),
				purchaseDate:$('#reviewInvoicePurchaseDate'),	
				value:$('#reviewInvoicesValue'),
				number:$('#reviewInvoicesNumber'),
				description:$('#reviewInvoicesDescription'),																										
			}
		d = {};
		for(var i in o){
			d[i]=o[i].val()
		}

		if(d.categories_id<1){
			alert('Please Select Category');
			o.categories_id.focus();
		}else if(d.value.length<1){
			alert('Please Input Value');
			o.value.focus();
		}else if(d.number.length<1){
			alert('Please Input number');
			o.number.focus();
		}else if(d.description.length<1){
			alert('Please Input Description');
			o.description.focus();
		}else if(d.id.length < 1){
			alert('Param Error,Please ')
			$('#editReceipt').modal('hide');
		}else if(d.purchaseDate.length<1){
			alert('Please Input purchaseDate');
			d.purchaseDate.focus();
		}else{
			$.ajax({
				url:'invoices/editInvoices',
				type:'POST',
				data:d,
				cache:false,
				dataType:'JSON',
				success:function(res){
					if(res.errno==0){
						$('#editReceipt').modal('hide');
						updateInvoicesCache(res.data);
						
						$('#tableID_'+res.data.id).replaceWith(updateInvoicesTable(res.data));
					}else{
						alert(res.msg);
					}
				}
			});
		}
		
	});
    $("#jcrop_image").on( "click", function() {
        initJcrop();
        $("#skipNextButton").addClass("disabled");
        $("#saveNextButton").addClass("disabled");
    });
   // $("#buttun_offset_top").offset().top;
    function initJcrop()//{{{
    {
      // Invoke Jcrop in typical fashion
      $('#skipImagePath').Jcrop({
        onChange:   showCoords,
        onSelect:   showCoords,
        onRelease:  releaseCheck,
        onDblClick: commitImage,
      },function(){
    	const_jcrop_api = this;
        jcrop_api = this;
        jcrop_api.animateTo([100,100,200,200]);

      });

    };
    
    
    // Simple event handler, called from onChange and onSelect
    // event handlers, as per the Jcrop invocation above
    function showCoords(c)
    {
        $('#startX').val(c.x);
        $('#startY').val(c.y);
        $('#endX').val(c.x2);
        $('#endY').val(c.y2);
    };

    function clearCoords()
    {
        $('#startX').val('nil');
        $('#startY').val('nil');
        $('#endX').val('nil');
        $('#endY').val('nil');
    };
    function releaseCheck()
    {
      jcrop_api.setOptions({ allowSelect: true });
      //$('#can_click').attr('checked',false);
      jcrop_api.destroy();
      $("#skipNextButton").removeClass("disabled");
      $("#saveNextButton").removeClass("disabled");

    };
    function cropImage(id,startX,startY,endX,endY)
    {
        if(id){
            $.post("/invoices/cropImage", {
                id: id,
                startX: startX,
                startY: startY,
                endX: endX,
                endY: endY
            }, function(res) {
                if(res) {
                	$("#skipImagePath").removeAttr("style");
                	updateInvoicesSkipData(res);
                	invoQueue.SetEnd(res);
                }
            },"JSON");
            
        }
    }
    function commitImage(){
    	id = $('#skipInvoicesId').val();
    	if(jcrop_api && id>0){
            var startX = $("#startX").val();
            var startY = $("#startY").val();
            var endX = $("#endX").val();
            var endY = $("#endY").val();
            
            jcrop_api.release();
            	
            cropImage(id,startX,startY,endX,endY);
            
            
            
        }
    }
    
   
})(jQuery)



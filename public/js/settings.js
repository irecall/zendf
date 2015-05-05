    	(function($){
        	
        	var enter = function(e){
				if(e.which == 13){
					e.data.modal.trigger('click');
				}
			}
			
			// Monitor ASIC 13
    		$('#addCompaniesModal').bind('keydown',{modal:$('#addCompaniesModal .modal-footer').find('button:last')},enter);
    		$('#addUserModal').bind('keydown',{modal:$('#addUserModal .modal-footer').find('button:last')},enter);
    		$('#addCategoryModal').bind('keydown',{modal:$('#addCategoryModal .modal-footer').find('button:last')},enter);
    		$('#addCurrencyModal').bind('keydown',{modal:$('#addCurrencyModal .modal-footer').find('button:last')},enter);
    		$('#companyDeleteModal').bind('keydown',{modal:$('#companyDeleteModal .modal-footer').find('button:last')},enter);
    		$('#userDeleteModal').bind('keydown',{modal:$('#userDeleteModal .modal-footer').find('button:last')},enter);
    		$('#categoryDeleteModal').bind('keydown',{modal:$('#categoryDeleteModal .modal-footer').find('button:last')},enter);
    		$('#currencyDeleteModal').bind('keydown',{modal:$('#currencyDeleteModal .modal-footer').find('button:last')},enter);
    		$('#currencyEditModal').bind('keydown',{modal:$('#currencyEditModal .modal-footer').find('button:last')},enter);

    		
    		//delete user
   	   		$('#Users-pills').on('click','tbody tr button',function(){
    			var _this = $(this);
    			var	_modal = $('#userDeleteModal');
    			_modal.find('h4:last').html(_this.parents('tr').find('td:nth-child(2)').html()); // set username in userDeleteModal
    			_modal.find('button:last').click(function(){
    				
    					$.ajax({
        					url:'settings/user/delete/'+_this.attr('userid'),
        					type:'GET',
        					cache:false,
        					dataType:'JSON',
        					success:function(msg){
        						if(msg.errno == 0){
        							_this.parents('tr').remove()
        						}else{
        						
        						}
        						_modal.modal('hide');
        					},
        					
        				});
    				
    			});
    		});
    		
    		//add Companies
    		$('#addCompaniesModal .modal-footer').find('button:last').click(function(){
                var d = {}; //form input value
                var o ={}; //form input object
    			var preg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                _form = $(this).parents('#addCompaniesModal');
                $.each(_form.find('.form-control'),function(i,n){
                    d[n.attributes["name"].value]= $.trim(n.value);
                    o[n.attributes["name"].value] = $(this);  
                })
                if(d.name.length<1 && d.name.length<30){
    				alert('Company name Must be greater than 1 characters and less than 30 characters');
    				o.name.focus();
    				return;
                }else if(!preg.test(d.email)){
    				alert('Email format error');
    				o.email.focus();
    				return;
                }else if(d.firstName.length<1 && d.lastName.length<10){
                    alert('First name Must be greater than 1 characters and less than 10 characters');
                    o.firstName.focus();
                    return;
                }else if(d.lastName.length<1 && d.lastName.length<10){
                    alert('Last name Must be greater than 1 characters and less than 10 characters');
                    o.lastName.focus();
                    return;
                }else{
    	            $.ajax({
    					url:'settings/company/add',
    					type:'POST',
    					cache:false,
    					data:$.param(d),
    					dataType:'JSON',
    					success:function(msg){
    							if(msg.errno == 0){
    								$("#Companies-pills").find('table tbody').append(msg.data.company);
    								$("#Users-pills").find('table tbody').append(msg.data.user);
    								_form.modal('hide');
    							}else{
    								alert(msg.message);
    							}
    						}
    	                });
                }
            });
            /**
            * companyList add detele button bind click 
            **/
            $('#Companies-pills').on('click',"tbody tr button",function(){
                var _this = $(this);
            	$('#companyDeleteModal .modal-footer').find('button:last').unbind('click').click(function(){
                   var _form = $(this).parents('#companyDeleteModal');
                   if(_form.find('.form-control').val() == 'Yes'){
        				$.ajax({
        					url:'settings/company/delete/'+_this.parents('tr').find('td:first').html(),
        					type:'GET',
        					cache:false,
        					dataType:'JSON',
        					success:function(res){

        							if(res.errno == 0){
            							_this.parents('tr').remove();
        								_form.modal('hide');
        							}else{
        								alert(res.message)
        							}
        						},
        					});
                   }else{
        				_form.modal('hide');
                   }
        		});
    		});
    		
    		
            // Add user
            $("#addUserSubmit").on("click",function(){
				var o = {
						username:$("#username"),
						password:$("#password"),
						enterPassword:$("#enterPassword"),
						fullName:$("#fullName"),
						company_id:$('#user_company_id')
						}
				var d = {
						username:o.username.val(),
						password:o.password.val(),
						enterPassword:o.enterPassword.val(),
						fullName:o.fullName.val(),
						company_id:o.company_id.val(),
						}
				if(d.fullName == ""){
					alert("Full Name cannot be empty");
					o.fullName.focus();
					return ;
				}else if(d.username == ""){
					alert("Username cannot be empty");
					o.username.focus();
					return;
				}else if(d.password == ""){
					alert("Password cannot be empty");
					o.password.focus();
					return ;
				}else if(d.password != d.enterPassword){
					alert("Two password is not consistent");
					o.password.val("");
					o.enterPassword.val("");
					o.password.focus();
					return ;
            	}else{
					delete d.enterPassword;
					var url = "settings/user/add";
					$.ajax({
    					url:url,
    					type:'POST',
    					cache:false,
    					data:d,
    					dataType:'JSON',
    					success:function(res){
    							if(res.errno==0){
									$("#userList").find("tbody").append(res.data.user);
									$("#addUserModal").modal("hide");
            					}else{
									alert(res.msg);
                    			}
    						},
    					});					
				}
            })
			// Add Category Ìí¼Ó·ÖÀà 
            $("#addCategorySubmit").on("click",function(){
				var category = $("#inputCategory");
				if(category.val() == ""){
					alert("description cannot be empty");
					category.focus();
					return;
				}else{
					$.ajax({
						url:"/settings/category/add",
						type:"POST",
						cache:false,
						data:{description:category.val(),company_id:$('#category_company_id').val()},
						dataType:"JSON",
						success:function(res){
							if(res.errno == 0){
								$("#Categories-pills table tbody").append(res.data.category);
								$("#addCategoryModal").modal("hide");
							}else{
								alert(res.msg);
							}
						}
					});
				}
            });

            //delete Category
			
            $('#Categories-pills').on('click',"tbody tr button",function(){
                var _this = $(this);
            	$('#categoryDeleteModal .modal-footer').find('button:last').unbind('click').click(function(){
                   var _form = $(this).parents('#categoryDeleteModal');
                   var replaceId = $('#categoryReplaceId').val();
                   var delId = _this.parents('tr').find('td:first').html();
                   if(replaceId>1){
                	   if(replaceId==delId){
                		   alert('Cannot Select Delete Category');
           				   $('#categoryReplaceId').focus();
           				   return;
                	   }
        				$.ajax({
        					url:'settings/category/delete/'+delId,
        					type:'POST',
        					cache:false,
        					dataType:'JSON',
        					data:{'udpId':replaceId},
        					success:function(res){

        							if(res.errno == 0){
            							_this.parents('tr').remove();
        								_form.modal('hide');
        							}else{
        								alert(res.message)
        							}
        						},
        					});
                   }else{
        				alert('Please Select Category');
        				$('#categoryReplaceId').focus();
                   }
        		});
    		});

            //Add Currency

       		$('#addCurrencySubmit').on('click',function(){
				var o = {
						currencyCode:$('#inputCurrencyCode'),
						contry:$('#inputContry'),
						applicableYear:$('#inputApplicableYear'),
						USDvalue:$('#inputUSDvalue'),
						company_id:$('#currency_company_id')
						};
				
				var d = {};

				if(o.currencyCode.val()==""){
					alert('currencyCode cannot be empty');
					o.currencyCode.focus();
				}else if(o.contry.val()==""){
					alert('contry cannot be empty');
					o.contry.focus();
					
				}else if(o.USDvalue.val()==""){
					alert('USDvalue cannot be empty');
					o.USDvalue.focus();
				}else if(o.applicableYear.val()==""){
					alert('applicableYear cannot be empty');
					o.applicableYear.focus();
				}else{
					for(var a in o){
						d[o[a].attr('name')]=o[a].val();
					}
					$.ajax({
						url:'/settings/currency/add',
						type:'POST',
						data:d,
						dataType:'JSON',
						cache:false,
						success:function(res){
							if(res.errno == 0){
    							$('#Currency-pills table tbody').append(res.data.html);
    							$('#addCurrencyModal').modal('hide');
							}else{
								alert(res.msg);
							}
						}
					});
				}
				
       	   	});

           	//Delete Currency
        	$('#Currency-pills').on('click',"tbody tr .delete-currency",function(){
                var _this = $(this);
            	$('#currencyDeleteModal .modal-footer').find('button:last').unbind('click').click(function(){
                   var _form = $(this).parents('#currencyDeleteModal');
                   if(_form.find('.form-control').val() != 'select'){
        				$.ajax({
        					url:'settings/currency/delete/'+_this.parents('tr').find('td:first').html(),
        					type:'GET',
        					cache:false,
        					dataType:'JSON',
        					success:function(res){

        							if(res.errno == 0){
            							_this.parents('tr').remove();
        								_form.modal('hide');
        							}else{
        								alert(res.message)
        							}
        						},
        					});
                   }else{
        				_form.modal('hide');
                   }
        		});
    		});

			//Edit Currency
        	$('#Currency-pills').on('click',"tbody tr .edit-currency",function(){
				var _tr = $(this).parents('tr');
				var _modal = $('#currencyEditModal');
				var usedData = new Array(5);
				
				_tr.children('td').each(function(i){
					if(i<5)
					usedData[i]=$(this).html();
				});
				_modal.find('#editCurrencyCode').val(usedData[1]);
				_modal.find('#editContry').val(usedData[2]);
				_modal.find('#editUSDvalue').val(usedData[3]);
				_modal.find('#editApplicableYear').val(usedData[4]);

				_modal.find('.modal-footer button:last').unbind('click').click(function(){
					var o = {
							currencyCode:$('#editCurrencyCode'),
							contry:$('#editContry'),
							applicableYear:$('#editApplicableYear'),
							USDvalue:$('#editUSDvalue'),
							
							};
					
					var d = {};

					if(o.currencyCode.val()==""){
						alert('currencyCode cannot be empty');
						o.currencyCode.focus();
					}else if(o.contry.val()==""){
						alert('contry cannot be empty');
						o.contry.focus();
						
					}else if(o.USDvalue.val()==""){
						alert('USDvalue cannot be empty');
						o.USDvalue.focus();
					}else if(o.applicableYear.val()==""){
						alert('applicableYear cannot be empty');
						o.applicableYear.focus();
					}else{
						for(var a in o){
							d[o[a].attr('name')]=o[a].val();
						}
						
        				$.ajax({
        					url:'settings/currency/edit/'+_tr.find('td:first').html(),
        					type:'POST',
        					cache:false,
        					data:d,
        					dataType:'JSON',
        					success:function(res){
        							if(res.errno == 0){
            							
            							_tr.replaceWith(res.data.html);
        								_modal.modal('hide');
        							}else{
        								alert(res.message)
        							}
        					},
						});
					}
				});
            });
        })(jQuery);

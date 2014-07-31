<head>
<style type="text/css">
.hidden{
	display: none;
}
</style>
<script type="text/javascript">
		var aJaxURL	= "server-side/call/tasks.action.php";		//server side folder url
		var upJaxURL		= "server-side/upload/file.action.php";	
		var tName	= "example";										//table name
		var fName	= "add-edit-form";									//form name
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () {   
			
			LoadTable();	

			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "","");			
			SetEvents("add_button", "", "", tName, fName, aJaxURL);
			
		});
        
		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list",9, "", 0, "", 1, "desc");
		}
		
		function LoadDialog(){
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 1060, "auto", "");
			var id = $("#incomming_id").val();
			var cat_id = $("#category_parent_id").val();

			$("#choose_button").button({
	            
	        });


			if(id != '' && cat_id == 407){
				$("#additional").removeClass('hidden');
			}
			GetDateTimes("planned_date");
			GetDateTimes("problem_date");

			$( ".calls" ).button({
			      icons: {
			        primary: " ui-icon-contact"
			      }
			});
		}
		
		function CloseDialog(){
			$("#" + fName).dialog("close");
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act						= "save_incomming";
		    
	    	param.id						= $("#id").val();
	    	param.phone						= $("#phone").val();
	    	param.call_date					= $("#call_date").val();
	    	param.pin						= $("#site_user_pin").val();
	    	
	    	param.call_type_id				= $("#call_type_id").val();
	    	param.category_id				= $("#category_id").val();
	    	param.category_parent_id		= $("#category_parent_id").val();
	    	param.problem_date				= $("#problem_date").val();
	    	param.call_status_id			= $("#call_status_id").val();
	    	param.call_content				= $("#call_content").val();
	    	param.persons_id				= $("#persons_id").val();
	    	param.comment					= $("#comment").val();
	    	param.task_department_id		= $("#task_department_id").val();
	    	param.task_type_id				= $("#task_type_id").val();
	    	param.priority_id				= $("#priority_id").val();
	    	param.problem_id				= $("#problem_id").val();
	    	param.pay_type_id				= $("#pay_type_id").val();
	    	param.bank_id					= $("#bank_id").val();
	    	param.bank_object_id			= $("#bank_object_id").val();
	    	param.card_type_id				= $("#card_type_id").val();
	    	param.card_type1_id				= $("#card_type1_id").val();
	    	param.pay_aparat_id				= $("#pay_aparat_id").val();
	    	param.object_id					= $("#object_id").val();
	    	param.personal_pin				= $("#personal_pin").val();
	    	param.personal_id				= $("#personal_id").val();
	    	param.personal_phone			= $("#personal_phone").val();
	    	param.mail						= $("#mail").val();
	    	param.operator_name				= $("#operator_name").val();
	    	param.name						= $("#name").val();
	    	param.user						= $("#user").val();
	    	param.problem_comment			= $("#problem_comment").val();
	    	param.call_duration				= $("#call_duration").val();
	    	param.c_date					= $("#c_date").val();
	    	param.fact_end_date				= $("#fact_end_date").val();
	    	param.planned_date				= $("#planned_date").val();
	    	param.template_id				= $("#template_id").val();
	    	param.rand_file					= rand_file;
	    	param.file_name					= file_name;
	    	param.hidden_inc				= $("#hidden_inc").val();
	    	
	    	
			if(param.req_phone == ""){
				alert("შეავსეთ ტელეფონის ნომერი!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable();
				        		CloseDialog();
				        		console.log(data.error);
							}
						}
				    }
			    });
			}
		});


	    $(document).on("click", "#download", function () {
	    	var download_file	= $(this).val();
	    	var download_name 	= $('#download_name').val();
	    	SaveToDisk(download_file, download_name);
	    });

	    function SaveToDisk(fileURL, fileName) {
	        // for non-IE
	        if (!window.ActiveXObject) {
	            var save = document.createElement('a');
	            save.href = fileURL;
	            save.target = '_blank';
	            save.download = fileName || 'unknown';

	            var event = document.createEvent('Event');
	            event.initEvent('click', true, true);
	            save.dispatchEvent(event);
	            (window.URL || window.webkitURL).revokeObjectURL(save.href);
	        }
		     // for IE
	        else if ( !! window.ActiveXObject && document.execCommand)     {
	            var _window = window.open(fileURL, "_blank");
	            _window.document.close();
	            _window.document.execCommand('SaveAs', true, fileName || fileURL)
	            _window.close();
	        }
	    } 
	   
	    $(document).on("click", "#choose_button", function () {
		    $("#choose_file").click();
		});

	    $(document).on("click", "#delete", function () {
	    	var delete_id	= $(this).val();
	    	
	    	$.ajax({
		        url: aJaxURL,
			    data: {
					act: "delete_file",
					delete_id: delete_id,
					edit_id: $("#id").val(),
				},
		        success: function(data) {
			        $("#file_div").html(data.page);
			    }
		    });	
		});

	    $(document).on("change", "#choose_file", function () {
	    	var file		= $(this).val();	    
	    	var files 		= this.files[0];
		    var name		= uniqid();
		    var path		= "../../media/uploads/file/";
		    
		    var ext = file.split('.').pop().toLowerCase();
	        if($.inArray(ext, ['pdf']) == -1) { //echeck file type
	        	alert('This is not an allowed file type.');
                this.value = '';
	        }else{
	        	file_name = files.name;
	        	rand_file = name + "." + ext;
	        	$.ajaxFileUpload({
	    			url: upJaxURL,
	    			secureuri: false,
	    			fileElementId: "choose_file",
	    			dataType: 'json',
	    			data:{
						act: "upload_file",
						path: path,
						file_name: name,
						type: ext
					},
	    			success: function (data, status){
	    				if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}
    					}
    							
	    				$.ajax({
					        url: aJaxURL,
						    data: {
								act: "up_now",
								rand_file: rand_file,
					    		file_name: file_name,
								edit_id: $("#id").val(),

							},
					        success: function(data) {
						        $("#file_div").html(data.page);
						    }
					    });	   					    				
    				},
    				error: function (data, status, e)
    				{
    					alert(e);
    				}    				
    			});
	        }
		});
		

	    function run(number){

	    	param 			= new Object();
		 	param.act		= "get_add_page";
		 	param.number	= number;
		 	
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#add-edit-form").html(data.page);
							LoadDialog();
						}
					}
			    }
		    });			
		    }

	    function runAjax() {
            $.ajax({
            	async: true,
            	dataType: "html",
		        url: 'AsteriskManager/auxstate_helper.php',
			    data: 'sesvar=hideloggedoff&value=true',
		        success: function(data) {
							$("#jq").html(data);						
			    }
            }).done(function(data) { 
                setTimeout(runAjax, 1000);
            });
        }

	    $(document).on("click", ".number", function () {
	    	var number = $(this).attr("number");
	    	if(number != ""){
	    		run(number);
	    		console.log(number);
		    } 
	    });

	    $(document).on("click", ".insert", function () {
	    	var phone = $(this).attr("number");
	    	console.log(phone);
	    	if(phone != ""){
	    		$('#phone').val(phone);
		    } 
	    });

	    $(document).on("change", "#bank_id",function(){
    	 	param 			= new Object();
		 	param.act		= "sub_bank_category";
		 	param.cat_id   	= this.value;		 	
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{							
							$("#bank_object_id").html(data.cat);							
						}
					}
			    }
		    });
	    });

	    $(document).on("change", "#task_type_id",function(){
		    var task_type = $("#task_type_id").val();

			if(task_type == 1){
				$("#task_department_id").val(37);
				$( "#additiona1" ).removeClass( "hidden" );
				$("#additiona3").addClass('hidden');
				$( "#additiona" ).addClass( "hidden" );
				$( "#additiona2" ).addClass( "hidden" );
				$("#category_parent_id").val(0);
			}else {
				$( "#additiona2" ).removeClass( "hidden" );
				$( "#additiona" ).removeClass( "hidden" );
				$( "#additiona1" ).addClass( "hidden" );
			}
		    
	    });

	    $(document).on("change", "#category_id",function(){
			if(this.value == 423){
				$(".friend").removeClass('hidden');
			}else{
				$(".friend").addClass('hidden');
			}
        });
		    
	    	 $(document).on("change", "#category_parent_id",function(){
		     	 	param 			= new Object();
		 		 	param.act		= "sub_category";
		 		 	param.cat_id   	= this.value;		 	
		 	    	$.ajax({
		 		        url: aJaxURL,
		 			    data: param,
		 		        success: function(data) {       
		 					if(typeof(data.error) != 'undefined'){
		 						if(data.error != ''){
		 							alert(data.error);
		 						}else{							
		 							$("#category_id").html(data.cat);							
		 						}
		 					}
		 			    }
		 		    });

			if(this.value == 407){
				$("#additiona3").removeClass('hidden');
			}else{
				$("#additiona3").addClass('hidden');
			}
        });

	    $(document).on("click", "#refresh-dialog", function () {
    	 	param 			= new Object();
		 	param.act		= "get_calls";
		 	
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#last_calls").html(data.calls);
							$( ".insert" ).button({
							      icons: {
							        primary: "ui-icon-plus"
							      }
							});
						}
					}
			    }
		    });
		    
	    });

	    $(document).on("click", ".calls", function () {
    	 	param 			= new Object();
		 	param.act		= "get_calls";
		 	
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#last_calls").html(data.calls);
							$( ".insert" ).button({
							      icons: {
							        primary: "ui-icon-plus"
							      }
							});
						}
					}
			    }
		    });
		    
	    	LoadDialogCalls();
	    	
	    });

	    function LoadDialogCalls(){
			var button = {
               		"save": {
               			text: "განახლება",
               			id: "refresh-dialog",
               			click: function () {
               			}
               		}
				};
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialogCalls('last_calls', 330, 550, button);
		}

		
    </script>
</head>

<body>



			
    <div id="dt_example" class="ex_highlight_row">
        <div id="container">        	
            <div id="dynamic" >
            	<h2 align="center">დავალებები</h2>
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        		</div>
        	
                <table class="display" id="example" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width:35px;">ID</th>
                            <th style="width: 60px;">user-ი</th>
                            <th style="width: 100px;">PIN კოდი</th>
                            <th style="width: 115px;">პრობლემის ტიპი</th>
                            <th style="width: 170px;">პასუხისმგებელი პირი</th>
                            <th style="width: 150px;">ოპერატორი</th>
                            <th style="width: 100%;">ზარის შ. თარიღი</th>
                            <th style="width: 170px;">სტატუსი</th>
                           
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 25px"/>
                            </th>
                            <th>
                            <input type="text" name="search_number" value="ფილტრი" class="search_init hidden-input" style="width: 20px">
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 40px"/>
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 85px"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 155px"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 135px" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 160px" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 160px" />
                            </th>
                            
                        </tr>
                    </thead>
                </table>
            	</div>
            <div class="spacer">
            </div>
        	</div>
   		 	</div>
	<!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="დავალება">
	</div>
	
	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
</body>
	
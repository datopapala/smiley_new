<head>
<style type="text/css">
.hidden{
	display: none;
}
</style>
<script type="text/javascript">
		var aJaxURL		= "server-side/call/tasks.action.php";		//server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";	
		var tName		= "example";										//table name
		var fName		= "add-edit-form";									//form name
		var file_name 	= '';
		var rand_file 	= '';
		
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
			GetDialog(fName, 1190, "auto", "");
			var id = $("#incomming_id").val();
			var cat_id = $("#category_parent_id").val();

			

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

		    param.act					= "save_incomming";
		    
		    param.id					= $("#id").val();
	    	param.id					= $("#task_id").val();
	    	param.person_id				= $("#person_id").val();
	    	param.problem_comment		= $("#problem_comment").val();
	    	param.status				= $("#status").val();
	    	param.comment1				= $("#comment1").val();
	    	
	    	param.c_id1					= $("#c_id1").val();
	    	
	    	param.priority_id			= $("#priority_id").val();
	    	param.task_status			= $("#task_status").val();
	    	param.template_id			= $("#template_id").val();
	    	param.person_id				= $("#person_id").val();
	    	param.task_type_id			= $("#task_type_id").val();
	    	param.task_date				= $("#task_date").val();
	    	
	    	param.persons_id			= $("#persons_id").val();
	    	param.comment				= $("#comment").val();
	    	param.task_department_id	= $("#task_department_id").val();
	    	param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
	    	param.problem_id			= $("#problem_id").val();
	    	param.pay_type_id			= $("#pay_type_id").val();
	    	param.bank_id				= $("#bank_id").val();
	    	
	    	
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
		
	    $(document).on("change", "#add_button", function () {
	    	var a12 = $("#id").val();

	    	if(a12 == ''){
				
				$( "#additiona5" ).addClass( "hidden_important" );
				
			}else {
				$( "#additiona5" ).removeClass( "hidden_important" );
				
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
	    $(document).on("keydown", "#personal_pin", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {
            	param 			= new Object();
    		 	param.act		= "get_add_info1";
    		 	param.pin_n		= $(this).val();
    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#info_c").html(data.info1);
    						}
    					}
    			    }
    		    });
                
                event.preventDefault();
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
                            <th style="width: 150px;">user-ი</th>
                           	<th style="width: 200px;">პასუხისმგებელი პირი</th>
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
	

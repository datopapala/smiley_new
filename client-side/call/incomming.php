<?php
require_once("AsteriskManager/config.php");
include("AsteriskManager/sesvars.php");
if(isset($_SESSION['QSTATS']['hideloggedoff'])) {
    $ocultar=$_SESSION['QSTATS']['hideloggedoff'];
} else {
    $ocultar="false";
}
?>


<head>
<style type="text/css">
.hidden{
	display: none;
}

#box-table-b
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #F8B64A;
	border-bottom: 7px solid #F8B64A;
}
#box-table-b th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #e8edff;
	border-right: 1px solid #CC840E;
	border-left: 1px solid #CC840E;
	color: #039;
}
#box-table-b td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #CC840E;
	border-left: 1px solid #CC840E;
	color: #669;
}
</style>
<script type="text/javascript">
		var aJaxURL	  = "server-side/call/incomming.action.php";		//server side folder url
		var upJaxURL  = "server-side/upload/file.action.php";	
		var tName	  = "example";										//table name
		var fName	  = "add-edit-form";									//form name
		var file_name = '';
		var rand_file = '';

		$(document).ready(function () {

			runAjax();
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

			GetDialog(fName, 1200, "auto", "");
			

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

		    param.act							= "save_incomming";
		    param.c_id1							= $("#c_id1").val();
	    	param.id							= $("#id").val();
	    	param.incom_date					= $("#incom_date").val();
	    	param.incom_phone					= $("#incom_phone").val();
	    	param.first_name					= $("#first_name").val();
			param.category_id					= $("#category_id").val();
	    	param.category_parent_id			= $("#category_parent_id").val();
	    	param.sale_date						= $("#sale_date").val();
	    	param.production_category_id		= $("#production_category_id").val();
	    	param.production_id					= $("#production_id").val();
	    	param.redirect						= $("#redirect").val();
	  	  	param.reaction_id					= $("#reaction_id").val();
	    	param.content						= $("#content").val();
	    	param.task_type_id					= $("#task_type_id").val();
	    	param.template_id					= $("#template_id").val();
	    	param.priority_id					= $("#priority_id").val();
	    	param.comment						= $("#comment").val();
	    	param.person_id						= $("#person_id").val();
	    	param.requester_type				= $('input[name=5]:checked').val();
	    	param.prod_status					= $('input[name=10]:checked').val();
	    	param.connect						= $('input[name=rad]:checked').val();
	    	
	    	param.file_name						= file_name;
	    	param.hidden_inc					= $("#hidden_inc").val();
	    	
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
		        url: 'AsteriskManager/liveState.php',
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

	    $(document).on("change", "#task_type_id",function(){
		    var task_type = $("#task_type_id").val();

			if(task_type == 1){
				$("#task_department_id").val(37);
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
        });

    	$(document).on("change", "#category_id",function(){
			if(this.value == 423){
				$(".friend").removeClass('hidden');
			}else{
				$(".friend").addClass('hidden');
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
//
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

<table style="width: 1100px; margin: 0 0 0 100px; padding-top:25px; display: block;">
		<tr style="width: 800px">
			<td>
            	<h2 align="center">შემომავალი ზარები</h2>
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        		</div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 35px;" >№</th>
                            <th style="width: 150px;">თარიღი</th>
                            <th style="width: 150px;">კატეგორია</th>
                            <th style="width: 150px;">ტელეფონი</th>
                            <th style="width: 150px;">შინაარსი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init hidden-input" style=""></th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>                            
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 90px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 90px;" />
                            </th>
                            
                        </tr>
                    </thead>
                </table>
	            <div class="spacer">
	         </div>
	    <td style="width: 20px;">
		   &nbsp;
		</td>
		<td style="width: 450px;">
		   <div id="jq" style="width: 450px; position: fixed;"></div>
		</td>
	</tr>
</table>

    <!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="შემომავალი ზარი">
	</div>

	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
</body>

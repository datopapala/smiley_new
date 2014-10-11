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
.download {

	background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#599bb3', endColorstr='#408c99',GradientType=0);
	background-color:#599bb3;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	
	text-decoration:none;
	text-shadow:0px 1px 0px #3d768a;
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
		var aJaxURL	  = "server-side/call/incomming.action.php";
		var aJaxURL1_2	  = "server-side/call/clients.action1_2.php";
		var aJaxURL1	  = "server-side/call/incomming.action1.php";
		var aJaxURL2	  = "server-side/call/incomming.action2.php";
		var aJaxURL3	  = "server-side/call/incomming.action3.php";		//server side folder url
		var upJaxURL  = "server-side/upload/file.action.php";	
		var tName	  = "example";										//table name
		var fName	  = "add-edit-form";									//form name
		var file_name = '';
		var rand_file = '';
		var tbName		= "tabs";

		$(document).ready(function () {
			GetTabs(tbName); 
			runAjax();
			GetTable0();
			$(document).on("change", "#production_category_id",function(){
	     	 	param 			= new Object();
	 		 	param.act		= "sub_produqtion";
	 		 	param.brand_id   	= this.value;
	 	    	$.ajax({
	 		        url: aJaxURL,
	 			    data: param,
	 		        success: function(data) {
	 					if(typeof(data.error) != 'undefined'){
	 						if(data.error != ''){
	 							alert(data.error);
	 						}else{
	 							$("#production_id").html(data.cat);
	 						}
	 					}
	 			    }
	 		    });
	        });
		    $(document).on("change", "#production_id",function(){
	     	 	param 			= new Object();
	 		 	param.act		= "sub_produqtion1";
	 		 	param.prod_id   = this.value;
	 		 	param.categ_id = $("#production_category_id").val();
	 	    	$.ajax({
	 		        url: aJaxURL,
	 			    data: param,
	 		        success: function(data) {
	 					if(typeof(data.error) != 'undefined'){
	 						if(data.error != ''){
	 							alert(data.error);
	 						}else{
	 							$("#production_brand_id").html(data.cat);
	 						}
	 					}
	 			    }
	 		    });
	        });
		});
		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2();
            }else if(tab == 3){
            	GetTable3();
            }
        });
		function GetTable0() {
            LoadTable();
            GetButtons("add_button", "","");
			SetEvents("add_button", "", "", tName, fName, aJaxURL);
        }
		function GetTable1() {
			 LoadTable1(0,0);
  			 GetDate("search_start_my");
  			 GetDate("search_end_my");
   			 $("#search_start_my").val('0000-00-00');
    	     $("#search_end_my").val('0000-00-00');
  			 SetEvents("", "", "", "example1", fName, aJaxURL);
   			 var start 	= $("#search_start").val();
			 var end 	= $("#search_end").val();
         }
         
		function GetTable2() {
			 var status	= $("input[name='status_n']:checked").val();
             LoadTable2(status);
             SetEvents("", "", "", "example2", fName, aJaxURL);
         }
         
		function GetTable3() {
				LoadTable3(0,0);
				SetEvents("", "", "", "example3", fName, aJaxURL);
				GetDate("search_start");
	  			GetDate("search_end");
	  			$("#search_start").val('0000-00-00');
	  	   	    $("#search_end").val('0000-00-00');
	  	   	    
				var start 	= $("#search_start").val();
				var end 	= $("#search_end").val();
	         }
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list",9, "", 0, "", 1, "desc");
		}
		function LoadTable1(start, end, status){
			var status = $("input[name='status_my_call']:checked").val();
			var start	= $("#search_start_my").val();
	    	var end		= $("#search_end_my").val();
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example1", aJaxURL1, "get_list",9, "start=" + start + "&end=" + end + "&status="+status, 0, "", 1, "desc");
		}
		
		function LoadTable2(status){
			var status = $("input[name='status_call_now']:checked").val();
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example2", aJaxURL2, "get_list&status="+status, 9, "", 0, "", 1, "desc");
		}
		
		function LoadTable3(start, end, status){
			var start	= $("#search_start").val();
			var end		= $("#search_end").val();
			var status = $("input[name='status_all_call']:checked").val();
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example3", aJaxURL3, "get_list", 9, "start=" + start + "&end=" + end + "&status="+status, 0, "", 1, "desc");
		}
		$(document).on("change", "#search_start", function () {
	    	var start	= $(this).val();
	    	var end		= $("#search_end").val();
	    	var status	= '';
	    	status = $("input[name='status_all_call']:checked").val();
	    	LoadTable3(start, end, status);
	    });
	    
	    $(document).on("change", "#search_end", function () {
	    	var start	= $("#search_start").val();
	    	var end		= $(this).val();
	    	var status	= '';
	    	status = $("input[name='status_all_call']:checked").val();
	    	LoadTable3(start, end, status);
	    });

	    $(document).on("change", "#search_start_my", function () {
	    	var start	= $(this).val();
	    	var end		= $("#search_end_my").val();
	    	var status	= '';
	    	status = $("input[name='status_my_call']:checked").val();
	    	LoadTable1(start, end, status);
	    });
	    
	    $(document).on("change", "#search_end_my", function () {
	    	var start	= $("#search_start_my").val();
	    	var end		= $(this).val();
	    	var status	= '';
	    	status = $("input[name='status_my_call']:checked").val();
	    	LoadTable1(start, end, status);
	    });

		$(document).on("change", "input[name='status_my_call']", function () {
			var start	= $("#search_start_my").val();
			var end		= $("#search_end_my").val();
	    	var status = $("input[name='status_my_call']:checked").val();
	    	LoadTable1(start, end, status);
	    });

		$(document).on("change", "input[name='status_call_now']", function () {
	    	var status = $("input[name='status_call_now']:checked").val();
	    	LoadTable2(status);
	    });

		$(document).on("change", "input[name='status_all_call']", function () {
			var start	= $("#search_start").val();
			var end		= $("#search_end").val();
	    	var status = $("input[name='status_all_call']:checked").val();
	    	LoadTable3(start, end, status);
	    });
		

		function LoadDialog(){

			GetDialog(fName, 1200, "auto", "");
			
			 $(document).on("click", "#button_calls", function () {
			LoadDialogCalls();
			$('#refresh-dialog').click(); })
			
			$( ".calls" ).button({
			      icons: {
			        primary: " ui-icon-contact"
			      }
			});
			GetDateTimes("sale_date");
			GetDataTable("examplee_1", aJaxURL1_2, "get_list", 10,"cl_id="+$("#c_id1").val(), 0, "", 1, "asc", "");
			
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
	    	param.production_brand_id			= $("#production_brand_id").val();
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
	    	param.connect						= $('input[name=check_]:checked').val();
	    	
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
								LoadTable1();
					        	LoadTable2();
					        	LoadTable3();
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
							$("#jq1").html(data);
			    }
            }).done(function(data) {
                setTimeout(runAjax, 1000);
            });
        }
	    $(document).on("click", ".download", function () {
            var link = ($(this).attr("str")).replace("audio:/var/spool/asterisk/monitor/", "");
      //      alert(link)
            link = 'http://212.72.155.176:8181/records/' + link + '.wav';

            window.open(link, 'chatwindow', "width=420,height=230,resizable=yes,scrollbars=yes,status=yes");
            
        });

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
	    		$('#incom_phone').val(phone);
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
            	param.pin_n		= $(this).val();
    		 	param.act		= "get_add_info1";
    		 	
    		 	
    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#info_c").html(data.info1);
    							GetDataTable("examplee_1", aJaxURL1_2, "get_list", 10,"cl_id="+$("#c_id1").val(), 0, "", 1, "asc", "");
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

<div id="tabs"; style="width:99%; height: 580px; margin-top: 25px; padding-top:-18px; display: block; ">
		<ul>
			<li><a href="#tab-0">ჩემი ზარები დღეს</a></li>
			<li><a href="#tab-1">ჩემი ზარები</a></li>
			<li><a href="#tab-2">ზარები დღეს</a></li>
			<li><a href="#tab-3">ყველა ზარი</a></li>
		</ul>
<div id="tab-0" >
		<table style="width: 1100px; margin: 0 0 0 30px; padding-top:10px; display: block;">
		<tr>
			<td style="width: 70%;">
			<div id="container" style="width: 95%;margin-top: -20px;">        	
		       <div id="dynamic">
            	<h2 align="center" >შემომავალი ზარები</h2>
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        		</div>
                <table class="display" id="example" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 80px;" >№</th>
                            <th style="width: 150px;">თარიღი</th>
                            <th style="width: 200px;">კატეგორია</th>
                            <th style="width: 100px;">ტელეფონი</th>
                            <th style="width: 100%;">შინაარსი</th>
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
		   </div>
	         </div>
	    <td style="width: 0px;">
		   &nbsp;
		</td>
		<td style="width: 30%;margin-top: 30px;">
		   <div id="jq" style="width: 450px; position: fixed;"></div>
		</td>
		
	</tr>
	</table>
</div>
<div id="tab-1">
		<table style="width: 1100px; margin: 0 0 0 30px; padding-top:25px; display: block;">
		<tr>
			<td style="width: 70%;">
			<div id="container" style="width: 95%;margin-top: -20px;">        	
		       <div id="dynamic">
            	<h2 align="center" >შემომავალი ზარები</h2>
            	<table style="position: absolute; width: 390px;">
				<tr>
				<td><input style="float: left;" type="radio" name="status_my_call" value="1" ><span style="margin-top:5px; display:block;">ინფორმაცია</span></td>
				<td><input style="float: left;" type="radio" name="status_my_call" value="2" ><span style="margin-top:5px; display:block;">პრეტენზია</span></td>
				<td><input style="float: left;" type="radio" name="status_my_call" value="3" ><span style="margin-top:5px; display:block;">შეთავაზება</span></td>
				<td><input style="float: left;" type="radio" name="status_my_call" value="4" ><span style="margin-top:5px; display:block;">სხვა</span></td>
				</tr>
				</table>
            	<div id="button_area" style="margin-top: 50px;">
	            	<div class="left" style="width: 250px;">
	            		<label for="search_start_my" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input style="width: 100px; margin-left: 5px; height: 18px;" type="text" name="search_start_my" id="search_start_my" class="inpt left"/>
	            	</div>
	            	<div class="right" style="">
	            		<label for="search_end_my" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
	            		<input style="width: 100px; margin-left: 5px; height: 18px;" type="text" name="search_end_my" id="search_end_my" class="inpt right" />
            		</div>	
            	</div>
            	<table class="display" id="example1" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 80px;" >№</th>
                            <th style="width: 150px;">თარიღი</th>
                            <th style="width: 200px;">კატეგორია</th>
                            <th style="width: 100px;">ტელეფონი</th>
                            <th style="width: 100%;">შინაარსი</th>
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
		   </div>
	         </div>
	    <td style="width: 0px;">
		   &nbsp;
		</td>
		<td style="width: 30%;margin-top: 30px;">
		   <div id="jq1" style="width: 450px; position: fixed;"></div>
		</td>
		
	</tr>
	</table>
</div>
<div id="tab-2">
		<table style="width: 100%;">
		<tr>
			<td>
			<div id="container" style="width: 100%;">        	
		       <div id="dynamic">
            	<h2 align="center" >შემომავალი ზარები</h2>
            	<table style="position: absolute; width: 390px;">
				<tr>
				<td><input style="float: left;" type="radio" name="status_call_now" value="1" ><span style="margin-top:5px; display:block;">ინფორმაცია</span></td>
				<td><input style="float: left;" type="radio" name="status_call_now" value="2" ><span style="margin-top:5px; display:block;">პრეტენზია</span></td>
				<td><input style="float: left;" type="radio" name="status_call_now" value="3" ><span style="margin-top:5px; display:block;">შეთავაზება</span></td>
				<td><input style="float: left;" type="radio" name="status_call_now" value="4" ><span style="margin-top:5px; display:block;">სხვა</span></td>
				</tr>
				</table>
            	<div id="button_area" style="margin-top: 20;">
            	</div>
            	<table class="display" id="example2" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 80px;" >№</th>
                            <th style="width: 150px;">თარიღი</th>
                            <th style="width: 200px;">კატეგორია</th>
                            <th style="width: 100px;">ტელეფონი</th>
                            <th style="width: 100%;">შინაარსი</th>
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
		   </div>
	         </div>
	</tr>
</table>
</div>
<div id="tab-3">
		<table style="width: 100%;">
		<tr>
			<td>
			<div id="container" style="width: 100%;">        	
		       <div id="dynamic">
            	<h2 align="center" >შემომავალი ზარები</h2>
            	<table style="position: absolute; width: 390px;">
				<tr>
				<td><input style="float: left;" type="radio" name="status_all_call" value="1" ><span style="margin-top:5px; display:block;">ინფორმაცია</span></td>
				<td><input style="float: left;" type="radio" name="status_all_call" value="2" ><span style="margin-top:5px; display:block;">პრეტენზია</span></td>
				<td><input style="float: left;" type="radio" name="status_all_call" value="3" ><span style="margin-top:5px; display:block;">შეთავაზება</span></td>
				<td><input style="float: left;" type="radio" name="status_all_call" value="4" ><span style="margin-top:5px; display:block;">სხვა</span></td>
				</tr>
				</table>
            	<div id="button_area" style="margin-top: 50px;">
	            	<div class="left" style="width: 250px;">
	            		<label for="search_start_my" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input style="width: 100px; margin-left: 5px; height: 18px;" type="text" name="search_start" id="search_start" class="inpt left"/>
	            	</div>
	            	<div class="right" style="">
	            		<label for="search_end_my" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
	            		<input style="width: 100px; margin-left: 5px; height: 18px;" type="text" name="search_end" id="search_end" class="inpt right" />
            		</div>	
            	</div>
                <table class="display" id="example3" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 80px;" >№</th>
                            <th style="width: 150px;">თარიღი</th>
                            <th style="width: 200px;">კატეგორია</th>
                            <th style="width: 100px;">ტელეფონი</th>
                            <th style="width: 100%;">შინაარსი</th>
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
		   </div>
	    </div>
	</tr>
</table>
</div>
</div>

    <!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="შემომავალი ზარი">
	</div>

	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
	
</body>

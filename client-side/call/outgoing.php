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
		var aJaxURL		= "server-side/call/outgoing/outgoing_tab0.action.php";		//server side folder url
		var aJaxURL1	= "server-side/call/outgoing/outgoing_tab1.action.php";		//server side folder url
		var aJaxURL2	= "server-side/call/outgoing/outgoing_tab2.action.php";		//server side folder url
		var aJaxURL3	= "server-side/call/outgoing/outgoing_tab3.action.php";		//server side folder url
        var seoyURL		= "server-side/seoy/seoy.action.php";					//server side folder url
		var upJaxURL		= "server-side/upload/file.action.php";	
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () {     
			GetTabs(tbName);   	
			GetTable0();
			SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");
			GetButtons("add_button","add_responsible_person");
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2()
            }else{
            	GetTable3()
            }
        });

		function GetTable0() {
            LoadTable0();
            SetEvents("add_button", "", "", "example0", fName, aJaxURL);
           
        }
        
		 function GetTable1() {
             LoadTable1();
             SetEvents("", "", "", "example1", "add-edit-form1", aJaxURL1);
         }
         
		 function GetTable2() {
             LoadTable2();
             SetEvents("", "", "", "example2", "add-edit-form2", aJaxURL2);
         }
         
		 function GetTable3() {
             LoadTable3();
             SetEvents("", "", "", "example3", fName, aJaxURL3);
         }

		 function LoadTable0(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example0", aJaxURL, "get_list", 6, "", 0, "", 1, "desc", "");
		}
			
		function LoadTable1(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example1", aJaxURL1, "get_list", 10, "", 0, "", 1, "desc", "");
		}

		function LoadTable2(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example2", aJaxURL2, "get_list",10, "", 0, "", 1, "desc", "");
		}
		
		function LoadTable3(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example3", aJaxURL3, "get_list", 10, "", 0, "", 1, "desc", "");
		}

		//SeoYyy
		$(document.body).click(function (e) {
        	$("#send_to").autocomplete("close");
        });

        function LoadDialog(fName){
            //alert(form);
			switch(fName){
				case "add-edit-form":
					var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-dialog"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        } 
				    };
					GetDialog("add-edit-form", 1184, "auto", buttons);
				break;	
				case "add-edit-form1":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog1"
				        }, 
						"save": {
				            text: "შენახვა",
				            id: "save-dialog1"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form1", 1184, "auto", buttons);
				break;	
				case "add-edit-form2":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog2"
				        }, 
				        "save": {
				            text: "შენახვა",
				            id: "save-dialog2"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form2", 1184, "auto", buttons);
			    break;
			}
			
			var id = $("#incomming_id").val();
			var cat_id = $("#category_parent_id").val();
	
			if(id != '' && cat_id == 407){
				$("#additional").removeClass('hidden');
			}
	
			GetDateTimes("planned_end_date");
			
			$( ".calls" ).button({
			      icons: {
			        primary: " ui-icon-contact"
			      }
			});
			$("#choose_button").button({
	            
		    });
		}

		function LoadDialog1(){
			var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save-printer",
			            click: function () {
			            	Change_person();			            
			            }
			        },
					"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			};
			GetDialog("add-responsible-person", 280, "auto", buttons);
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
	    	param.task_type_id			= $("#task_type_id").val();
	    	param.task_date				= $("#task_date").val();
	    	
	    	
		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable0();
							CloseDialog("add-edit-form");
						}
					}
			    }
		    });
		});

		
	    $(document).on("click", "#save-dialog1", function () {
		   
	    	 param 			= new Object();

			    param.act					= "save_outgoing1";
			    
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
		    	param.task_type_id			= $("#task_type_id").val();
		    	param.task_date				= $("#task_date").val();
		    	
		    
	 
 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable1();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});
	    $(document).on("click", "#done-dialog1", function () {
	    	 param 			= new Object();

			    param.act					= "done_outgoing";
			    
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
	 
 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable1();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});

	    $(document).on("click", ".download", function () {
            var link = ($(this).attr("str")).replace("audio:/var/spool/asterisk/monitor/", "");
      //      alert(link)
            link = 'http://212.72.155.176:8181/records/' + link + '.wav';

            window.open(link, 'chatwindow', "width=420,height=230,resizable=yes,scrollbars=yes,status=yes");
            
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

	   $(document).on("click", "#save-dialog2", function () {
			param 				= new Object();
 			param.act			= "save_outgoing2";
		    	
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
		    	param.task_type_id			= $("#task_type_id").val();
		    	param.task_date				= $("#task_date").val();
		    
	 
	 
 	    	$.ajax({
 			        url: aJaxURL2,
 				    data: param,
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								LoadTable2();
 								CloseDialog("add-edit-form2");
 							}
						}
 				    }
 			});
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
//
	    $(document).on("keydown", "#personal_id", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {

            	param 					= new Object();
    		 	param.act				= "get_add_info1";
    		 	param.personal_id		= $("#personal_id").val();

    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#additional_info").html(data.info1);
    						}
    					}
    			    }
    		    });
                
                event.preventDefault();
            }
        });
	    $(document).on("click", "#done-dialog2", function () {
			param 				= new Object();
 			param.act			= "done_outgoing";
		    	
 			param.id					= $("#id").val();
			param.id1					= $("#id1").val();
	    	param.call_date				= $("#call_date").val();
	    	param.problem_date			= $("#problem_date").val();
			param.persons_id			= $("#persons_id").val();
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			param.planned_end_date		= $("#planned_end_date").val();
			param.fact_end_date			= $("#fact_end_date").val();
			param.call_duration			= $("#call_duration").val();
			param.phone					= $("#phone").val();
			param.comment				= $("#comment").val();
			param.problem_comment		= $("#problem_comment").val();
	 
 	    	$.ajax({
 			        url: aJaxURL2,
 				    data: param,
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								LoadTable2();
 								CloseDialog("add-edit-form2");
 							}
						}
 				    }
 			});
 		});
	 function SetPrivateEvents(add,check,formName){
		$(document).on("click", "#" + add, function () {    
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: "act=get_responsible_person_add_page",
	            dataType: "json",
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#" + formName).html(data.page);
	                        if ($.isFunction(window.LoadDialog)){
	                            //execute it
	                        	LoadDialog1();
	                        }
	                    }
	                }
	            }
	        });
	    });
		
	    $(document).on("click", "#" + check, function () {
	    	$("#" + tName + " INPUT[type='checkbox']").prop("checked", $("#" + check).is(":checked"));
	    });	
	}

	function Change_person(formName){
	    var data = $(".check:checked").map(function () {
	        return this.value;
	    }).get();
	    
	    var letters = [];
	    
	    for (var i = 0; i < data.length; i++) {
	    	letters.push(data[i]);        
	    }
    	param = new Object();
    	param.act	= "change_responsible_person";
    	param.lt	= letters;
	    param.rp	= $("#responsible_person").val();

	    var link	=  GetAjaxData(param);
	    
	    if(param.rp == "0"){
		    alert("აირჩიეთ პასუხისმგებელი პირი!");
		}else if(param.ci == "0"){
		    alert("აირჩიეთ ავტომობილი");		
		}else{	    
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: link,
	            dataType: "json", 
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#add-responsible-person").dialog("close");
	                        LoadTable0();
	                    }
	                }
	            }
	        });
		}	    		
	}
    
		
    </script>
</head>

<body>

<div id="tabs" style="width: 100%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">მენეჯერი</a></li>
			<li><a href="#tab-1">პირველადი</a></li>
			<li><a href="#tab-2">მიმდინარე</a></li>
			<li><a href="#tab-3">დასრულებული</a></li>
		</ul>
		<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გამავალი ზარები</h2>
		            	<div id="button_area">
		            		<button id="add_button">დამატება</button>
	        				<button id="add_responsible_person">პ. პირის აქტივაცია</button>
	        			</div>
		                <table class="display" id="example0">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:80px;">#</th>
									<th style="width:33%;">user-ი</th>
									<th style="width:33%;">პასუხისმგებელი პირი</th>
									<th style="width:33%;">ოპერატორი</th>
									<th style="width:150px;">ზარის შ.თარიღი</th>
									<th class="check">#</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 25px"/>
                            		</th>
									<th>
										<input style="width:85px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input type="checkbox" name="check-all" id="check-all-in"/>
									</th>
									
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		<div id="tab-1">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გამავალი ზარები</h2>
		                <table class="display" id="example1">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:80px;">#</th>
									<th style="width:33%;">user-ი</th>
									<th style="width:33%;">პასუხისმგებელი პირი</th>
									<th style="width:33%;">ოპერატორი</th>
									<th style="width:150px;">ზარის შ.თარიღი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th>
										<input style="width:50px;" type="text" name="search_id" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-2">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გამავალი ზარები</h2>
		                <table class="display" id="example2">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:80px;">#</th>
									<th style="width:33%;">user-ი</th>
									<th style="width:33%;">პასუხისმგებელი პირი</th>
									<th style="width:33%;">ოპერატორი</th>
									<th style="width:150px;">ზარის შ.თარიღი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th>
										<input style="width:50px;" type="text" name="search_id" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-3">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გამავალი ზარები</h2>
		                <table class="display" id="example3">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:80px;">#</th>
									<th style="width:33%;">user-ი</th>
									<th style="width:33%;">პასუხისმგებელი პირი</th>
									<th style="width:33%;">ოპერატორი</th>
									<th style="width:150px;">ზარის შ.თარიღი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th>
										<input style="width:50px;" type="text" name="search_id" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<div id="add-responsible-person" class="form-dialog" title="პასუხისმგებელი პირი">
<!-- aJax -->
</div>
</body>


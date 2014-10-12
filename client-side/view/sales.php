<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/view/sales/sales.action.php";		//server side folder url
		var aJaxURL1	= "server-side/view/sales/sales.action1.php";
		var aJaxURL1_1	= "server-side/view/sales/sales.action1_1.php";		//server side folder url
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';

		$(document).ready(function () {

			GetTabs(tbName);
			GetTable0();
			GetButtons("add_button","add_responsible_person");
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
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

		 function LoadTable0(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example0", aJaxURL, "get_list", 13, "", 0, "", 1, "asc", [4]);
		}

		function LoadTable1(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example1", aJaxURL1, "get_list", 13, "", 0, "", 1, "asc", [4]);
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
					GetDialog("add-edit-form", 1080, "auto", buttons);
					GetDateTimes("mont_date");
					GetDataTable("examplee_2", aJaxURL1_1, "get_list", 10,"cl_id="+$("#hh_id").val(), 0, "", 1, "asc", "");
				break;
				case "add-edit-form1":
					var buttons = {
						 "save": {
					            text: "შენახვა",
					            id: "save-dialog1",
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
					GetDialog("add-edit-form1", 1084, "auto", buttons);
					GetDataTable("examplee_1", aJaxURL1_1, "get_list", 10,"cl_id="+$("#h_id").val(), 0, "", 1, "asc", "");
					GetDateTimes("mont_date");
				break;
				
			}
		}

		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {

			param 			= new Object();
			param.act			= "save_sale";

			param.id					= $("#id").val();
			param.h_id					= $("#hh_id").val();
	    	param.mont_date				= $("#mont_date").val();
	    	param.problem_date			= $("#problem_date").val();
		
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			
			param.template_id			= $("#template_id").val();
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

			param 				= new Object();
 			param.act			= "save_sale";

 			param.id					= $("#id").val();
			param.h_id					= $("#h_id").val();
			param.mont_date				= $("#mont_date").val();
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
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

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

    </script>
<style type="text/css">
table tr td:nth-child(5),table tr th:nth-child(5){
   text-align: right;
  }
</style>
</head>

<body>

<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">მიმდინარე გაყიდვები</a></li>
			<li><a href="#tab-1">გაყიდვების არქივი</a></li>
		</ul>
		<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">გაყიდვები</h2>
		                <table class="display" id="example0" style="width: 100%;">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:15%; word-break:break-all;">თარიღი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:18%; word-break:break-all;">თანხა</th>
									<th style="width:21%; word-break:break-all;">ქვე-განყოფილება</th>
									<th style="width:19%; word-break:break-all;">საწყობი</th>
									<th style="width:21%; word-break:break-all;">გატანის თარიღი</th>
									<th style="width:21%; word-break:break-all;">მიტანის თარიღი</th>
									<th style="width:21%; word-break:break-all;">მონტაჟის თარიღი</th>
									<th style="width:18%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:37px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
								</tr>
							</thead>
							<tfoot>
		                        <tr>
		                        	<th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                        </tr>
		                    </tfoot>
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
		            	<h2 align="center">არქივი</h2>
		                <table class="display" id="example1">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:15%; word-break:break-all;">თარიღი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:18%; word-break:break-all;">თანხა</th>
									<th style="width:21%; word-break:break-all;">ქვე-განყოფილება</th>
									<th style="width:19%; word-break:break-all;">საწყობი</th>
									<th style="width:21%; word-break:break-all;">გატანის თარიღი</th>
									<th style="width:21%; word-break:break-all;">მიტანის თარიღი</th>
									<th style="width:21%; word-break:break-all;">მონტაჟის თარიღი</th>
									<th style="width:18%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:37px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
								</tr>
							</thead>
							<tfoot>
		                        <tr>
		                        	<th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                            <th>&nbsp;</th>
		                        </tr>
		                    </tfoot>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="გაყიდვები">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="გაყიდვები">
<!-- aJax -->
</div>

</body>


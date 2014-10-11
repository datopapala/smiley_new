<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/info/clients/clients.action.php";		//server side folder url
		var aJaxURL1	= "server-side/info/clients/clients.action1.php";		//server side folder url
		var aJaxURL2	= "server-side/info/clients/clients.action2.php";
		var aJaxURL3	= "server-side/info/clients/clients.action3.php";		//server side folder url
		var aJaxURL4	= "server-side/info/clients/clients.action4.php";		//server side folder url
		var aJaxURL5	= "server-side/info/clients/clients.action5.php";
		var aJaxURL6	= "server-side/info/clients/clients.action6.php";
		var aJaxURL1_1	= "server-side/info/clients/clients.action1_1.php";		//server side folder url
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';

		$(document).ready(function () {
			GetTabs(tbName);
			GetTable0();
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();

        		var subtab = GetSelectedTab("subtabs");
            	if (subtab == 0) {
            		GetTable3();
            		LoadTable4();
        	    	SetEvents("", "", "", "example4", "add-edit-form2", aJaxURL4);
            	}else if(subtab == 1){
            		GetTable4();
            		LoadTable5();
        	    	SetEvents("", "", "", "example5", "add-edit-form3", aJaxURL5);
            	 }else if(subtab == 2){
            		GetTable5();
            		LoadTable6();
        	    	SetEvents("", "", "", "example6", "add-edit-form4", aJaxURL6);
            	 }
            }else if(tab == 2){
        		GetTable2();
            }
        });


		function GetTable0() {
            LoadTable0();
            SetEvents("add_button", "", "", "example0", fName, aJaxURL);

        }

		 function GetTable1() {
             LoadTable1();
         }

		 function GetTable2() {
             LoadTable2();
            SetEvents("", "", "", "example2", "add-edit-form5", aJaxURL3);
        	GetDateTimes("gift_date");
         }

		function GetTable3() {
	    	LoadTable4();
	    	SetEvents("", "", "", "example4", "add-edit-form2", aJaxURL4);
	   }

		function GetTable4() {
	    	LoadTable5();
	    	SetEvents("", "", "", "example5", "add-edit-form3", aJaxURL5);
	     }

		function GetTable5() {
	    	LoadTable6();
	    	SetEvents("", "", "", "example6", "add-edit-form4", aJaxURL6);
	   }
		var total=6;
		function LoadTable0(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example0", aJaxURL, "get_list", 10, "", 0, "", 1, "asc", [8]);
		}

		function LoadTable1(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example1", aJaxURL2, "get_list", 10, "", 0, "", 1, "asc", [8]);
		}

		function LoadTable2(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example2", aJaxURL3, "get_list", 10, "", 0, "", 1, "asc", [8]);
			SetEvents("", "", "", "example4", "add-edit-form2", aJaxURL4);
		}

		function LoadTable4(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example4", aJaxURL4, "get_list", 10, "", 0, "", 1, "asc", [8]);
			SetEvents("", "", "", "example4", "add-edit-form2", aJaxURL4);

		}

		function LoadTable5(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example5", aJaxURL5, "get_list", 10, "", 0, "", 1, "asc", [8]);
			SetEvents("", "", "", "example5", "add-edit-form3", aJaxURL5);

		}

		function LoadTable6(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable2("example6", aJaxURL6, "get_list", 10, "", 0, "", 1, "asc", [8]);
			SetEvents("", "", "", "example6", "add-edit-form4", aJaxURL6);

		}

		function LoadTable3(){

			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("examplee", aJaxURL1, "get_list", 10,"id="+$("#id").val(), 0, "", 1, "asc", "");
			SetEvents("", "", "", "examplee", "add-edit-form1", aJaxURL1);
			GetDateTimes("gift_date");
		}
	function LoadTable3_1(){

			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("examplee_", aJaxURL1_1, "get_list", 10,"id="+$("#id").val(), 0, "", 1, "asc", '');

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
					GetDialog("add-edit-form", 1195, "auto", buttons);
					GetDateTimes("born_date");
					GetButtons("add_button_p","");
					GetDataTable("examplee", aJaxURL1, "get_list", 10,"cl_id="+$("#cl_id").val(), 0, "", 1, "asc", "");
					SetEvents("add_button_p", "", "", "examplee", "add-edit-form1", aJaxURL1, "tb=1");
					GetDateTimes("gift_date");
					GetDataTable("examplee_1", aJaxURL1_1, "get_list", 10,"cl_id="+$("#cl_id").val(), 0, "", 1, "asc", "");


				break;
				case "add-edit-form1":
					var buttons = {

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

					GetDialog("add-edit-form1", 400, "auto", buttons);

					$( "#gift_price" ).focus();
					GetDateTimes("gift_date");
					GetDateTimes("gift_date1");
					GetDateTimes("gift_date2");
					GetDateTimes("gift_date3");
					GetDataTable("examplee", aJaxURL1, "get_list", 10,"id="+$("#id").val(), 0, "", 1, "asc", "");

				break;
				case "add-edit-form2":
					var buttons = {
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
					GetDialog("add-edit-form2", 1195, "auto", buttons);
					GetDateTimes("born_date");
					GetDataTable("examplee1", aJaxURL1, "get_list", 10,"cl_id="+$("#v_id").val(), 0, "", 1, "asc", "");
					SetEvents("", "", "", "examplee1", "add-edit-form1", aJaxURL1, "tb=2");
					GetDataTable("examplee_3", aJaxURL1_1, "get_list", 10,"cl_id="+$("#v_id").val(), 0, "", 1, "asc", "");


			    break;
				case "add-edit-form3":
					var buttons = {
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
					GetDialog("add-edit-form3", 1195, "auto", buttons);
					GetDateTimes("born_date");
					GetDataTable("examplee2", aJaxURL1, "get_list", 10,"cl_id="+$("#P_id").val(), 0, "", 1, "asc", "");
					SetEvents("", "", "", "examplee2", "add-edit-form1", aJaxURL1, "tb=3");
					GetDataTable("examplee_4", aJaxURL1_1, "get_list", 10,"cl_id="+$("#P_id").val(), 0, "", 1, "asc", "");

				break;
				case "add-edit-form4":
					var buttons = {
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
					GetDialog("add-edit-form4", 1195, "auto", buttons);
					GetDateTimes("born_date");
					GetDataTable("examplee3", aJaxURL1, "get_list", 10,"cl_id="+$("#G_id").val(), 0, "", 1, "asc", "");
					SetEvents("", "", "", "examplee3", "add-edit-form1", aJaxURL1, "tb=4");
					GetDataTable("examplee_5", aJaxURL1_1, "get_list", 10,"cl_id="+$("#G_id").val(), 0, "", 1, "asc", "");


			    break;
				case "add-edit-form5":
					var buttons = {
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
					GetDialog("add-edit-form5", 1195, "auto", buttons);
					GetDateTimes("born_date");
					GetDataTable("examplee4", aJaxURL1, "get_list", 10,"cl_id="+$("#l_id").val(), 0, "", 1, "asc", "");
					SetEvents("", "", "", "examplee4", "add-edit-form1", aJaxURL1, "tb=5");
					GetDataTable("examplee_2", aJaxURL1_1, "get_list", 10,"cl_id="+$("#l_id").val(), 0, "", 1, "asc", "");


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
			GetDialog1("add-responsible-person", 280, "auto", buttons);
		}

	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {

			param 			= new Object();
			param.act			= "save_client";

			param.id					= $("#id").val();
	    	param.legal_status_id		= $("#legal_status_id").val();
	    	param.client_pin			= $("#client_pin").val();
	    	param.client_name			= $("#client_name").val();
			param.born_date				= $("#born_date").val();
			param.client_mobile1		= $("#client_mobile1").val();
	    	param.client_mobile2		= $("#client_mobile2").val();
			param.client_phone			= $("#client_phone").val();
			param.client_mail			= $("#client_mail").val();
			param.Juristic_address		= $("#Juristic_address").val();
			param.Juristic_city			= $("#Juristic_city").val();
			param.Juristic_postal_code	= $("#Juristic_postal_code").val();
			param.physical_address		= $("#physical_address").val();
			param.physical_city			= $("#physical_city").val();
			param.physical_postal_code	= $("#physical_postal_code").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.priority_id			= $("#priority_id").val();
			param.problem_comment		= $("#problem_comment").val();
			param.client_comment		= $("#client_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable2();
							CloseDialog("add-edit-form");
						}
					}
			    }
		    });
		});
	    $(document).on("click", "#save-dialog", function () {

			param 			= new Object();
			param.act			= "save_client";

			param.id					= $("#id").val();
	    	param.legal_status_id		= $("#legal_status_id").val();
	    	param.client_pin			= $("#client_pin").val();
	    	param.client_name			= $("#client_name").val();
			param.born_date				= $("#born_date").val();
			param.client_mobile1		= $("#client_mobile1").val();
	    	param.client_mobile2		= $("#client_mobile2").val();
			param.client_phone			= $("#client_phone").val();
			param.client_mail			= $("#client_mail").val();
			param.Juristic_address		= $("#Juristic_address").val();
			param.Juristic_city			= $("#Juristic_city").val();
			param.Juristic_postal_code	= $("#Juristic_postal_code").val();
			param.physical_address		= $("#physical_address").val();
			param.physical_city			= $("#physical_city").val();
			param.physical_postal_code	= $("#physical_postal_code").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.priority_id			= $("#priority_id").val();
			param.problem_comment		= $("#problem_comment").val();
			param.client_comment		= $("#client_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

		    $.ajax({
		        url: aJaxURL3,
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
	    $(document).on("click", "#save-dialog2", function () {

			param 			= new Object();
			param.act			= "save_client2";

			param.id					= $("#id").val();
	    	param.legal_status_id		= $("#legal_status_id").val();
	    	param.client_pin			= $("#client_pin").val();
	    	param.client_name			= $("#client_name").val();
			param.born_date				= $("#born_date").val();
			param.client_mobile1		= $("#client_mobile1").val();
	    	param.client_mobile2		= $("#client_mobile2").val();
			param.client_phone			= $("#client_phone").val();
			param.client_mail			= $("#client_mail").val();
			param.Juristic_address		= $("#Juristic_address").val();
			param.Juristic_city			= $("#Juristic_city").val();
			param.Juristic_postal_code	= $("#Juristic_postal_code").val();
			param.physical_address		= $("#physical_address").val();
			param.physical_city			= $("#physical_city").val();
			param.physical_postal_code	= $("#physical_postal_code").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.priority_id			= $("#priority_id").val();
			param.problem_comment		= $("#problem_comment").val();
			param.client_comment		= $("#client_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

		    $.ajax({
		        url: aJaxURL4,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable4();
							CloseDialog("add-edit-form2");
						}
					}
			    }
		    });
		});

	    $(document).on("click", "#save-dialog3", function () {

			param 			= new Object();
			param.act			= "save_client3";

			param.id					= $("#id").val();
	    	param.legal_status_id		= $("#legal_status_id").val();
	    	param.client_pin			= $("#client_pin").val();
	    	param.client_name			= $("#client_name").val();
			param.born_date				= $("#born_date").val();
			param.client_mobile1		= $("#client_mobile1").val();
	    	param.client_mobile2		= $("#client_mobile2").val();
			param.client_phone			= $("#client_phone").val();
			param.client_mail			= $("#client_mail").val();
			param.Juristic_address		= $("#Juristic_address").val();
			param.Juristic_city			= $("#Juristic_city").val();
			param.Juristic_postal_code	= $("#Juristic_postal_code").val();
			param.physical_address		= $("#physical_address").val();
			param.physical_city			= $("#physical_city").val();
			param.physical_postal_code	= $("#physical_postal_code").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.priority_id			= $("#priority_id").val();
			param.problem_comment		= $("#problem_comment").val();
			param.client_comment		= $("#client_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

		    $.ajax({
		        url: aJaxURL5,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable5();
							CloseDialog("add-edit-form3");
						}
					}
			    }
		    });
		});
	    $(document).on("click", "#save-dialog4", function () {

			param 			= new Object();
			param.act			= "save_client4";

			param.id					= $("#id").val();
	    	param.legal_status_id		= $("#legal_status_id").val();
	    	param.client_pin			= $("#client_pin").val();
	    	param.client_name			= $("#client_name").val();
			param.born_date				= $("#born_date").val();
			param.client_mobile1		= $("#client_mobile1").val();
	    	param.client_mobile2		= $("#client_mobile2").val();
			param.client_phone			= $("#client_phone").val();
			param.client_mail			= $("#client_mail").val();
			param.Juristic_address		= $("#Juristic_address").val();
			param.Juristic_city			= $("#Juristic_city").val();
			param.Juristic_postal_code	= $("#Juristic_postal_code").val();
			param.physical_address		= $("#physical_address").val();
			param.physical_city			= $("#physical_city").val();
			param.physical_postal_code	= $("#physical_postal_code").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.priority_id			= $("#priority_id").val();
			param.problem_comment		= $("#problem_comment").val();
			param.client_comment		= $("#client_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();

		    $.ajax({
		        url: aJaxURL6,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable6();
							CloseDialog("add-edit-form4");
						}
					}
			    }
		    });
		});
	    $(document).on("click", "#save-dialog1", function () {

			param 				= new Object();
 			param.act			= "save_client_gift";

 			param.id1					= $("#id").val();
 			param.id_g					= $("#id_g").val();
			param.gift_date				= $("#gift_date").val();
			param.gift_date1			= $("#gift_date1").val();
			param.gift_date2			= $("#gift_date2").val();
			param.gift_date3			= $("#gift_date3").val();
			param.gift_date4			= $("#gift_date4").val();
			param.gift_production_id	= $("#gift_production_id").val();
	    	param.gift_price			= $("#gift_price").val();



 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable3();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});

	   $(document).on("click", "#save-dialog2", function () {
			param 				= new Object();
 			param.act			= "save_outgoing";

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
	    $(document).on("keydown", "#personal_pin", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {

            	param 			= new Object();
    		 	param.act		= "get_add_info";
    		 	param.pin		= $("#personal_pin").val();

    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#additional_info").html(data.info);
    						}
    					}
    			    }
    		    });

              //  event.preventDefault();
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
			$("#additional").removeClass('hidden');
		}else{
			$("#additional").addClass('hidden');
		}
    });


	    $(document).on("keyup", "#req_time1, #req_time2", function() {
	        var val = $(this).val();
	        if(isNaN(val) || (val>60)){

	         alert("მოცემულ ველში შეიყვანეთ მხოლოდ ციფრები");
	             val = val.replace(/[^0-9\.]/g,'');
	             if(val.split('.').length>2)
	                 val =val.replace(/\.+$/,"");
	        }
	        $(this).val(val);
	    });

	    $(document).on("change", "#task_type_id",function(){
		    var task_type = $("#task_type_id").val();

			if(task_type == 1){
				$("#task_department_id").val(37);
			}

	    });

    </script>
 <style type="text/css">
table tr td:nth-child(9),table tr th:nth-child(9){
   text-align: right;
  }
    </style>
</head>

<body>

<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">კლიენტები</a></li>
			<li><a href="#tab-1">VIP კლიენტები</a></li>
			<li><a href="#tab-2">ლოიალური კლიენტები</a></li>
		</ul>
		<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">კლიენტები</h2>
		                <table class="display" id="example0" style="width: 100%;">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:19%; word-break:break-all;">პირადი ნომერი</th>
									<th style="width:19%; word-break:break-all;">იურ. სტატუსი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:21%; word-break:break-all;">ტელეფონი</th>
									<th style="width:19%; word-break:break-all;">ელ-ფოსტა</th>
									<th style="width:21%; word-break:break-all;">შეძენების<br>რაოდენობა</th>
									<th style="width:21%; word-break:break-all;">ჯამური ნავაჭრი<br>თანხა</th>
									<th style="width:21%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:40px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		<div id="subtabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#subtab-0">VIP-A</a></li>
			<li><a href="#subtab-1">VIP-B</a></li>
			<li><a href="#subtab-2">VIP-C</a></li>
		</ul>
		<div id="subtab-0">
		 <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">VIP კლიენტები</h2>
		                <table class="display" id="example4">
		                <thead>

								<tr id="datatable_header">
									 <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:19%; word-break:break-all;">პირადი ნომერი</th>
									<th style="width:19%; word-break:break-all;">იურ. სტატუსი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:21%; word-break:break-all;">ტელეფონი</th>
									<th style="width:19%; word-break:break-all;">ელ-ფოსტა</th>
									<th style="width:21%; word-break:break-all;">შეძენების<br>რაოდენობა</th>
									<th style="width:21%; word-break:break-all;">ჯამური ნავაჭრი<br>თანხა</th>
									<th style="width:21%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:40px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		                        </tr>
		                    </tfoot>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>

		 <div id="subtab-1">
		 <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">VIP კლიენტები</h2>
		                <table class="display" id="example5">
		                <thead>

								<tr id="datatable_header">
									 <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:19%; word-break:break-all;">პირადი ნომერი</th>
									<th style="width:19%; word-break:break-all;">იურ. სტატუსი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:21%; word-break:break-all;">ტელეფონი</th>
									<th style="width:19%; word-break:break-all;">ელ-ფოსტა</th>
									<th style="width:21%; word-break:break-all;">შეძენების<br>რაოდენობა</th>
									<th style="width:21%; word-break:break-all;">ჯამური ნავაჭრი<br>თანხა</th>
									<th style="width:21%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:40px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		                        </tr>
		                    </tfoot>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>

		 <div id="subtab-2">
		 <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">VIP კლიენტები</h2>
		                <table class="display" id="example6">
		                <thead>

								<tr id="datatable_header">
									 <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:19%; word-break:break-all;">პირადი ნომერი</th>
									<th style="width:19%; word-break:break-all;">იურ. სტატუსი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:21%; word-break:break-all;">ტელეფონი</th>
									<th style="width:19%; word-break:break-all;">ელ-ფოსტა</th>
									<th style="width:21%; word-break:break-all;">შეძენების<br>რაოდენობა</th>
									<th style="width:21%; word-break:break-all;">ჯამური ნავაჭრი<br>თანხა</th>
									<th style="width:21%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:40px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		  </div>



		 <div id="tab-2">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">
		            <div id="dynamic">
		            	<h2 align="center">ლოიალური კლიენტები</h2>
		                <table class="display" id="example2">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:7%;">#</th>
									<th style="width:19%; word-break:break-all;">პირადი ნომერი</th>
									<th style="width:19%; word-break:break-all;">იურ. სტატუსი</th>
									<th style="width:19%; word-break:break-all;">კონტრაგენტი</th>
									<th style="width:21%; word-break:break-all;">ტელეფონი</th>
									<th style="width:19%; word-break:break-all;">ელ-ფოსტა</th>
									<th style="width:21%; word-break:break-all;">შეძენების<br>რაოდენობა</th>
									<th style="width:21%; word-break:break-all;">ჯამური ნავაჭრი<br>თანხა</th>
									<th style="width:21%; word-break:break-all;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:40px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
<div id="add-edit-form" class="form-dialog" title="კლიენტები">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="VIP briliant">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form3" class="form-dialog" title="VIP Platinim">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form4" class="form-dialog" title="VIP Gold">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form5" class="form-dialog" title="ლოიალური">
<!-- aJax -->
</div>


<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="საჩუქარი">
<!-- aJax -->
</div>
</body>


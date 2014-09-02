<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/call/action/action.action.php";		//server side folder url
		var aJaxURL1	= "server-side/call/action/action.action1.php";
		var aJaxURL2	= "server-side/call/action/action.action2.php";		//server side folder url
		var upJaxURL  = "server-side/upload/file.action.php";
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
			GetDataTable("example0", aJaxURL, "get_list", 7, "", 0, "", 1, "asc", "");
		}
			
		function LoadTable1(){	
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example1", aJaxURL1, "get_list", 7, "", 0, "", 1, "asc", "");
		}
		function LoadTable2(){						
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example3", aJaxURL2, "get_list", 6,"action_idd="+$("#action_id").val(), 0, "", 1, "asc", "");
		}
		

	//SeoYyy
		$(document.body).click(function (e) {
        	$("#send_to").autocomplete("close");
        });

        function LoadDialog(fName){ 
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
						GetDateTimes("start_date");
						GetDateTimes("end_date");
						LoadTable2();

						SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");//----------------------------------
						GetButtons("add_button_p","");
						SetEvents("add_button_p", "", "", "example3", "add-edit-form2", aJaxURL2, "action_id="+$('#action_id').val());						
						
						
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
					GetDialog("add-edit-form1", 1060, "auto", buttons);
					GetButtons("add_button_p1","");
					GetDataTable("example4", aJaxURL2, "get_list", 6, "", 0, "", 1, "asc", "");
					SetEvents("add_button_p1", "", "", "example4", "add-edit-form2", aJaxURL2, "action_id="+$('#action_id').val());
					
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
					GetDialog("add-edit-form2", 400, "auto", buttons);
					
					GetDateTimes("date");
			    break;
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

		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {

			param 			= new Object();
			param.act			= "save_action";			
			param.id					= $("#id").val();			
			param.id				= $("#action_id").val();
			param.action_name		= $("#action_name").val();
			param.start_date		= $("#start_date").val();
			param.end_date			= $("#end_date").val();
			param.task_type_id	    = $("#task_type_id").val();
			param.template_id		= $("#template_id").val();
			param.priority_id		= $("#priority_id").val();
			param.comment			= $("#comment").val();
			param.action_content	= $("#action_content").val();
			param.person_id			= $("#person_id").val();person_id
			param.rand_file			= rand_file;
	    	param.file_name			= file_name;
	    	param.hidden_inc		= $("#action_id").val();
	 
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

		$(document).on("click", "#save-dialog2", function () {
	    	
			param 					= new Object();
			param.local_id			= $("#action_id").val();
 			param.act				= "save_action_1";
		    	
 			param.id				= $("#id").val();
			param.production_id		= $("#production_id").val();
	    	param.object_id			= $("#object_id").val();
	    	param.action_id			= $("#action_id").val();
	    	param.price				= $("#price").val();
			param.date				= $("#date").val();
			
			
	 
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
	
	
	 $(document).on("click", "#download", function () {
	      var download_file = $(this).val();
	      var download_name  = $('#download_name').val();
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
	      var delete_id = $(this).val();
	      
	      $.ajax({
	          url: aJaxURL,
	       data: {
	     act: "delete_file",
	     delete_id: delete_id,
	     edit_id: $("#action_id").val(),
	    },
	          success: function(data) {
	           $("#file_div").html(data.page);
	       }
	      }); 
	  });

	     $(document).on("change", "#choose_file", function () {
	      var file  = $(this).val();     
	      var files   = this.files[0];
	      var name  = uniqid();
	      var path  = "../../media/uploads/file/";
	      
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
	        edit_id: $("#action_id").val(),

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
</head>

<body>

<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">მიმდინარე აქციები</a></li>
			<li><a href="#tab-1">აქციების არქივი</a></li>
		</ul>
		<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 90%;">        	
		            <div id="dynamic">
		            	<h2 align="center">აქციები</h2>
		            	<div id="button_area">
		            		<button id="add_button">დამატება</button>
	        			</div>
		                <table class="display" id="example0" style="width: 100%;">
		                    <thead>
								<tr id="datatable_header">
		                           <th>ID</th>
									<th style="width:6%;">#</th>
									<th style="width:15%; word-break:break-all;">დასახელება</th>
									<th style="width:13%; word-break:break-all;">დასაწყისი</th>
									<th style="width:13%; word-break:break-all;">დასასრული</th>
									<th style="width:40%; word-break:break-all;">შინაარსი</th>
									<th style="width:15%; word-break:break-all;">ავტორი</th>
									
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
		<div id="tab-1">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">არქივი</h2>
		                <table class="display" id="example1">
		                    <thead>
								<tr id="datatable_header">
		                           <th>ID</th>
									<th style="width:6%;">#</th>
									<th style="width:15%; word-break:break-all;">დასახელება</th>
									<th style="width:13%; word-break:break-all;">დასაწყისი</th>
									<th style="width:13%; word-break:break-all;">დასასრული</th>
									<th style="width:40%; word-break:break-all;">შინაარსი</th>
									<th style="width:15%; word-break:break-all;">ავტორი</th>
									
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
<div id="add-edit-form" class="form-dialog" title="აქცია">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="აქცია">
<!-- aJax -->
</div>

<div id="add-responsible-person" class="form-dialog" title="პასუხისმგებელი პირი">
<!-- aJax -->
</div>
</body>


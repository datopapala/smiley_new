<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/report/callsContent.action.php";	//server side folder url	
		var tName		= "example";											//table name
		var fName		= "view-form";										//form name
		
		$(document).ready(function () {
			    	
			LoadTable(0, 0 ,tName);

			GetDate("search_start");
			GetDate("search_end");
		});
        
		function LoadTable(start, end, table){
			switch(table){
				default	:
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable(tName, aJaxURL, "get_list", 6, "start=" + start + "&end=" + end, 0, "", 1, "asc");
			}
		}
		
		function CloseDialog(){
			$("#" + fName).dialog("close");
		}
	    
	    $(document).on("change", "#search_start", function () {
	    	var start	= $(this).val();
	    	var end		= $("#search_end").val();
	    	LoadTable(start, end, tName);
	    });
	    
	    $(document).on("change", "#search_end", function () {
	    	var start	= $("#search_start").val();
	    	var end		= $(this).val();
	    	LoadTable(start, end, tName);
	    });
	    
	    function CheckStatus(status) {
	    	if(status == 0){
			    DisableLegal();
			    EnablePhysicall();
			}else{
				DisablePhysicall();
			    EnableLegal();
			}
		}
        
	    function DisableLegal() {
            $("#company_name").attr("disabled", "disabled");
            $("#company_name").val("");
            $("#area").attr("disabled", "disabled");
            $("#area").val("");
            $("#street").attr("disabled", "disabled");
            $("#street").val("");
        }
        
	    function EnableLegal() {
            $("#company_name").removeAttr('disabled');
            $("#area").removeAttr('disabled');
            $("#street").removeAttr('disabled');
        }
        
	    function DisablePhysicall() {
            $("#first_name").attr("disabled", "disabled");
            $("#first_name").val("");
            $("#last_name").attr("disabled", "disabled");
            $("#last_name").val("");
            $("#age").attr("disabled", "disabled");
            $("#age").val("");
            $("#phone").attr("disabled", "disabled");
            $("#phone").val("");
            $("#address").attr("disabled", "disabled");
            $("#address").val("");
        }
        
	    function EnablePhysicall() {
            $("#first_name").removeAttr('disabled');
            $("#last_name").removeAttr('disabled');
            $("#age").removeAttr('disabled');
            $("#phone").removeAttr('disabled');
            $("#address").removeAttr('disabled');
        }
		
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row">
        <div id="container" style="width: 95%;">        	
            <div id="dynamic">
            	<h2 align="center">ზარები შინაარსობრივად</h2>
            	<div id="button_area">
				     <div class="left" style="width: 250px;">
					      <label for="search_start" class="left"
					       style="margin: 5px 0 0 9px;">დასაწყისი</label> 
						       <input type="text"
						       name="search_start" id="search_start" class="inpt right date"
						       style="padding: 0px !important; height: 25px !important; width: 165px !important;" />
				     </div>
				     <div class="right" style="width: 260px;">
				      		<label for="search_end" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
				      			<input type="text" name="search_end" id="search_end"
				       				class="inpt right date"
				       					style="padding: 0px !important; height: 25px !important; width: 165px !important;" />
				     </div>
				    </div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th class="min">ინფორმაცია</th>
                            <th class="min">პრეტენზია</th>
                            <th class="min">შეთავაზება</th>
                            <th class="min">სხვა</th>                           
                            <th style="width: 210px">სულ დამუშავებული ზარი</th>  
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_info" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_claim" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_offer" value="ფილტრი" class="search_init" />
                            </th> 
                            <th>
                                <input type="text" name="search_other" value="ფილტრი" class="search_init" />
                            </th>                                                           
                            <th>
                                <input type="text" name="search_all" value="ფილტრი" class="search_init" />
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
    <div id="view-form" title="ზარები შინაარსობრივი კატეგორიების მიხედვით">
    	<!-- aJax -->
	</div>
</body>
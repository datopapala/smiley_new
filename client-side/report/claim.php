<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/report/claim.action.php";	//server side folder url
		var tName	= "example";											//table name
		var fName	= "add-edit-form";										//form name
		
		$(document).ready(function () {
			    	
			LoadTable(0, 0, 0);

			GetDate("search_start");
			GetDate("search_end");
		});
        
		function LoadTable(start, end, category){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 3, "start=" + start + "&end=" + end + "&cat=" + category, 0, "", 0, "asc");
		}
	    
	    $(document).on("change", "#search_start", function () {
	    	var start		= $(this).val();
	    	var end			= $("#search_end").val();
	    	var category	= $("#category").val();
	    	LoadTable(start, end, category);
	    });
	    
	    $(document).on("change", "#search_end", function () {
	    	var start		= $("#search_start").val();
	    	var end			= $(this).val();
	    	var category	= $("#category").val();
	    	LoadTable(start, end, category);
	    });

	    $(document).on("change", "#category", function () {
	    	var start	= $("#search_start").val();
	    	var end		= $("#search_end").val();
	    	var category		= $(this).val();
	    	LoadTable(start, end, category);
	    });
        
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
        <div id="container" style="width: 90%;">        	
            <div id="dynamic">
            	<h2 align="center">აბონენტთა პრეტენზიები</h2>
            	<div id="button_area">

	            	<div class="left" style="width: 250px;">
	            		<label for="search_start" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input type="text" name="search_start" id="search_start" class="inpt right"/>
	            	</div>
	            	<div class="right" style="width: 700px;display:inline;">
	            		<label for="search_end" class="left" style="margin: 5px 0 0 9px;display: inline;">დასასრული</label>
	            		<input type="text" name="search_end" id="search_end" class="inpt" style="display: inline;"/>
	            		<label for="interest_production_category" style="display: inline;">პროდუქტები</label>
						<select class="inpt" id="category"style="display:inline;width: 200px;">
							<option value="0" selected="selected">კატეგორიების მიხედვით</option>
							<option value="1" >პროდუქტების მიხედვით</option>												
						</select>
            		</div>
            	</div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">კატეგორიის ტიპი</th>
                            <th style="width: 100%;">რაოდენობა</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_age" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_quantity" value="ფილტრი" class="search_init" />
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
    <div id="add-edit-form" title="">
    	<!-- aJax -->
	</div>
</body>
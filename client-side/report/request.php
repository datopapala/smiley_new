<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/report/request.action.php";	//server side folder url
		var tName	= "example";											//table name
		var fName	= "add-edit-form";										//form name
		
		$(document).ready(function () {
			    	
			LoadTable(0, 0);
			
			SetEvents("", "", "", tName, fName, aJaxURL);
			GetDate("search_start");
			GetDate("search_end");
		});
        
		function LoadTable(start, end){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 7, "start=" + start + "&end=" + end, 0, "", 1, "asc");
		}
		
		$(document).on("change", "#search_start", function () {
	    	var start	= $(this).val();
	    	var end		= $("#search_end").val();
	    	LoadTable(start, end);
	    });
	    
	    $(document).on("change", "#search_end", function () {
	    	var start	= $("#search_start").val();
	    	var end		= $(this).val();
	    	LoadTable(start, end);
	    });

    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row">
        <div id="container" style="width: 95%;">        	
            <div id="dynamic">
            	<h2 align="center">მომართვები</h2>
            	<div id="button_area">
				     <div class="left" style="width: 250px;">
				      <label for="search_start" class="left"
				       style="margin: 5px 0 0 9px;">დასაწყისი</label> <input type="text"
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
                            <th style="width: 60px">№</th>
                            <th class="min">თარიღი</th>
                            <th class="min">ტელეფონი</th>
                            <th style="width: 180px">ზარის კატეგორია</th>
                            <th style="width: 273px">შინაარსი</th>
                            <th style="width: 200px">ოპერატორი</th>
                            
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th><input type="text" name="search_number" value="ფილტრი" class="search_init hidden-input"></th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_requester" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_content" value="ფილტრი" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="spacer">
            </div>
        </div>
    </div>
    
	
</body>
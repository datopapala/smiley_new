<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/report/research.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		    	
		$(document).ready(function () {        	
			LoadTable();	
						
			/* Add Button ID, Delete Button ID */		
			SetEvents("", "", "", tName, fName, aJaxURL);
		});
        
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 9, "", 0, "", 2, "desc");
    		
		}
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 98%; margin: 0 auto;">
        <div id="container" style="width: 98%; margin: 0 auto;">        	
            <div id="dynamic">
            	<h2 align="center">კვლევა</h2>
            	
                <table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">ინტერვიუერი</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th style="width: 100%;">მომხმარებელი</th>
                            <th style="width: 100%;">ფილიალი</th>
                            <th style="width: 100%;">ტელეფონი</th>
                            <th style="width: 100%;">რჩევები</th>
                            <th style="width: 100%;">პერსონალის<br> შეფასება</th>
                            <th style="width: 100%;">ზარის დაზუსტება</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden"></th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                          	<th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="ქალაქი">
    	<!-- aJax -->
	</div>
</body>
</html>



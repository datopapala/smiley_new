<html>
<head>
 <style type="text/css">
table tr td:nth-child(3){
   text-align: right;
  }
table tr td:nth-child(4){
   text-align: right;
  }
    </style>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/client_clasification.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		    	
		$(document).ready(function () {        	
			LoadTable();	
						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");			
			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
		});
        
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 4, "", 0, "", 1, "desc");
    		
		}
		
		function LoadDialog(){
			var id		= $("#id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 390, "auto", "");
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act			="save_clasification";
	    	param.id			= $("#id").val();
	    	param.name			= $("#name").val();
	    	param.amount_end	= $("#amount_end").val();
	    	param.amount_start	= $("#amount_start").val();
	    	
			if(param.name == ""){
				alert("შეავსეთ ველი!");
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
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}
		});

	   
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 1024px; margin: 0 auto;">
        <div id="container">        	
            <div id="dynamic">
            	<h2 align="center">კლიენეტების კლასიფიკაცია</h2>
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        			<button id="delete_button">წაშლა</button>
        		</div>
                <table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 70%;">დასახელება</th>
                            <th style="width: 15%;">თანხა-დან</th>
                             <th style="width: 15%;">თანხა-მდე</th>
                        	<th class="check">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
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
                            	<input type="checkbox" name="check-all" id="check-all">
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="ობიექტი">
    	<!-- aJax -->
	</div>
</body>
</html>


<?php

require_once("AsteriskManager/config.php");
//include("AsteriskManager/sesvars.php");

?>

<head>
	<script type="text/javascript">					
		      
		$(document).ready(function () {  	  
	       runAjax();  
	       runAjax1();  		    
		});

		function runAjax() {
            $.ajax({
            	async: false,
            	dataType: "html",
		        url: 'AsteriskManager/liveState.php',
			    data: 'sesvar=hideloggedoff&value=true',
		        success: function(data) {
					$("#jq").html(data);						
			    }
            }).done(function(data) { 
                setTimeout(runAjax, 1000);
            });
		}

		function runAjax1() {
            $.ajax({
            	async: true,
            	dataType: "html",
		        url: 'server-side/call/flash_panel.action.php',
		        success: function(data) {
					$("#level").html(data);						
			    }
            }).done(function(data) { 
                setTimeout(runAjax1, 1000);
            });
		}
		
    </script>
</head>

<style type='text/css'>
	#hor-zebra
	{
		font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
		font-size: 12px;
		text-align: center;
		border-collapse: collapse;
	}
	#hor-zebra th
	{
		font-size: 14px;
		font-weight: normal;
		padding: 10px 8px;
		color: #039;
	}
	#hor-zebra td
	{
		padding: 8px;
		color: #669;
	}
	#hor-zebra .odd
	{
		background: #e8edff; 
	}
	
	
#box-table-b
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #9baff1;
	border-bottom: 7px solid #9baff1;
}
#box-table-b th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #e8edff;
	border-right: 1px solid #9baff1;
	border-left: 1px solid #9baff1;
	color: #039;
}
#box-table-b td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #aabcfe;
	border-left: 1px solid #aabcfe;
	color: #669;
}
</style>

<body>
    <table style=" margin: 0 auto;" border="1">
		<tr>				
			<td>		
			   <div id="jq" style="height: 520px; margin-top: 50px;"></div>
			</td>
			<td style="width: 20px;">
			</td>
			<td>
				<p style="margin-top: 50px;">Service Level</p>
				<div id="level" style="height: 380px; margin-top: 16px;"></div>
			</td>			
		</tr>
	</table>
</body>
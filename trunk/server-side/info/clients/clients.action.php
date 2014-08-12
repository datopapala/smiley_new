<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//incomming
$client_id				= $_REQUEST['id'];
$client_name			= $_REQUEST['client_name'];
$client_status			= $_REQUEST['legal_status_id'];
$client_pin				= $_REQUEST['client_pin'];
$born_date				= $_REQUEST['born_date'];
$client_mobile1			= $_REQUEST['client_mobile1'];
$client_mobile2			= $_REQUEST['client_mobile2'];
$client_phone			= $_REQUEST['client_phone'];
$client_mail			= $_REQUEST['client_mail'];
$Juristic_address 		= $_REQUEST['Juristic_address'];
$Juristic_city			= $_REQUEST['Juristic_city'];
$Juristic_postal_code	= $_REQUEST['Juristic_postal_code'];
$physical_address		= $_REQUEST['physical_address'];
$physical_city			= $_REQUEST['physical_city'];
$physical_postal_code	= $_REQUEST['physical_postal_code'];

//task
$task_type_id			= $_REQUEST['task_type_id'];
$template_id			= $_REQUEST['template_id'];
$priority_id			= $_REQUEST['priority_id'];
$problem_comment		= $_REQUEST['problem_comment'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];


switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$incom_id				= $_REQUEST['id'];
		mysql_query("			UPDATE `incomming_call`
									    SET `actived`=0
										WHERE `id`=$incom_id ");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming());

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT 	`client`.`id`,
										`client`.`id`,
										`client`.`code`,
										`legal_status`.`name`,
										`client`.`name`,
										`client`.`phone`,
										`client`.`mail`,
										`client`.`name`,
										`client`.`name`,
	  									`client`.`name`
								FROM 	`client`
								JOIN 	`legal_status` ON `client`.`legal_status_id` = `legal_status`.`id`");
	  
		$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_client':
		$client_id				= $_REQUEST['id'];		
		if($client_id == ''){			
			Addclient(  $client_name,  $client_status, $client_pin, $client_phone, $client_mail, $born_date, $client_mobile1, $client_mobile2, $Juristic_address, $Juristic_city,  $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code);
			$task_type_id = $_REQUEST['task_type_id'];			
			if($task_type_id != ''){
			$incomming_call_id = mysql_insert_id();
			Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id, $problem_comment);
			
			}
		}else {
			Saveclient($client_name, $client_status, $client_pin, $born_date, $client_mobile1, $client_mobile2, $client_phone, $client_mail, $Juristic_address, $Juristic_city, $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code);
			Savetask();
			
			
		}
		break;
		
	
	case 'get_add_info':
	
		$pin	=	$_REQUEST['pin'];
		$data 	= 	array('info' => get_addition_all_info($pin));
	
		break;
		case 'get_add_info1':
		
		$personal_id	=	$_REQUEST['personal_id'];
		$data 	= 	array('info1' => get_addition_all_info1($personal_id));
	
		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function Addclient(  $client_name,  $client_status, $client_pin, $client_phone, $client_mail, $born_date, $client_mobile1, $client_mobile2, $Juristic_address, $Juristic_city,  $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code){
	
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `client` 
							(`name`, `legal_status_id`, `code`, `phone`, `mail`, `born_date`, `mobile1`, `mobile2`, `Juristic_address`, `Juristic_city`, `Juristic_postal_code`, `physical_address`, `physical_city`, `physical_postal_code`)
 						VALUES 
							( '$client_name', '$client_status', '$client_pin', '$client_phone', '$client_mail',' $born_date', '$client_mobile1', '$client_mobile2', '$Juristic_address', '$Juristic_city', '$Juristic_postal_code', '$physical_address', '$physical_city','$physical_postal_code');");
	
	
}

function Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id, $problem_comment)
{	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
								( `user_id`, `incomming_call_id`, `template_id`, `task_type_id`, `priority_id`,  `problem_comment`, `status`, `actived`) 
							VALUES 
								( '$user', '$incomming_call_id', '$template_id', '$task_type_id', '$priority_id',  '$problem_comment', '0', '1');");
	
	
}


				
function Saveclient($client_name, $client_status, $client_pin, $born_date, $client_mobile1, $client_mobile2, $client_phone, $client_mail, $Juristic_address, $Juristic_city, $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code)
{
	$client_id				= $_REQUEST['id'];
	$user		= $_SESSION['USERID'];
	mysql_query("	UPDATE `client` SET 
									`name`='$client_name', 
									`legal_status_id`='$client_status', 
									`code`='$client_pin', 
									`phone`='$client_phone', 
									`mail`='$client_mail', 
									`born_date`='$born_date', 
									`mobile1`='$client_mobile1', 
									`mobile2`='$client_mobile2', 
									`Juristic_address`='$Juristic_address', 
									`Juristic_city`='$Juristic_city', 
									`Juristic_postal_code`='$Juristic_postal_code', 
									`physical_address`='$physical_address', 
									`physical_city`='$physical_city', 
									`physical_postal_code`='$physical_postal_code' 
					WHERE			`id`='$client_id'
			");
	

}       
function Savetask()
{	$id						= $_REQUEST['id'];
	$task_type_id			= $_REQUEST['task_type_id'];
	$template_id			= $_REQUEST['template_id'];
	$priority_id			= $_REQUEST['priority_id'];
	$problem_comment		= $_REQUEST['problem_comment'];
	
	$user  = $_SESSION['USERID'];
	mysql_query(" UPDATE `task` SET 
								`user_id`='$user', 
								`responsible_user_id`='', 
								`date`='', 
								`template_id`='$template_id', 
								`task_type_id`='$task_type_id', 
								`priority_id`='$priority_id', 
								`comment`='', 
								`problem_comment`='$problem_comment', 
								`status`='0', 
								`actived`='1'
				 WHERE 			`incomming_call_id`='$id'
			");
				

}

function Getpriority($priority_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $priority_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Gettask_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `task_type`
					    WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Get_template($template_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
							FROM `template`
							WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $template_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Get_legal_status($client_status)
{
	$data = '';
	$req = mysql_query("SELECT 	legal_status.id,
								legal_status.`name` AS `name`
						FROM 	legal_status
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $client_status){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function getCalls(){
	$db1 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

						SELECT  	DISTINCT
									IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		12


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
				</tr>
			';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass">' . $i . '</td>
					<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
				</tr>';
		$i++;
	}

	return $data;


}


function Getincomming()
{
	
$res = mysql_fetch_assoc(mysql_query("	SELECT 	client.id,
												client.legal_status_id AS legal_status_id ,
												client.`code` AS client_pin,
												client.`name` AS client_name,	
												client.born_date AS born_date,
												client.mobile1 AS client_mobile1,
												client.mobile2 AS client_mobile2,
												client.phone   AS client_phone,
												client.mail		AS client_mail,
												client.Juristic_address AS Juristic_address,
												client.Juristic_city AS Juristic_city,
												client.Juristic_postal_code AS Juristic_postal_code,
												client.physical_address AS physical_address,
												client.physical_city AS physical_city,
												client.physical_postal_code AS physical_postal_code,
												task.task_type_id AS task_type_id,
												task.template_id AS template_id,
												task.priority_id AS priority_id,
												task.problem_comment AS problem_comment
										FROM    client
										
										left JOIN    task ON client.id = task.incomming_call_id
										WHERE   client.id= ".$_REQUEST['id']."	
			" ));
	
	return $res;
}
function GetLocalID(){
	GLOBAL $db;
	return $db->increment('client');
}


function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}
	
	$data  .= '
	<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 500px;">	
				<fieldset >
			    	<legend>საკონტაკტო ინფო</legend>
					<fieldset style="width:665px; float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>სტატუსი</td>
								<td>VIP-კლიენტი</td>
								<td></td>
								<td></td>								
							</tr>
							<tr>
								<td>კონტრაგენტი</td>
								<td>
									<input type="text" id="client_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_name']. '"  />
								</td>
								<td>მობილური 1</td>
								<td>
									<input type="text" id="client_mobile1" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_mobile1']. '"  />
								</td>
											
							</tr>
							<tr>
								<td>იურ. სტატუსი</td>
								<td>
									<select id="legal_status_id" class="idls object">'.Get_legal_status($res['legal_status_id']).'</select>
								</td>
								<td>მობილური 2</td>
								<td>
									<input type="text" id="client_mobile2" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_mobile2']. '"  />
								</td>
										
							</tr>	
							<tr>
								<td>პირადი ნომერი</td>
								<td>
									<input type="text" id="client_pin" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_pin']. '"  />
								</td>
								<td>ტელეფონი</td>
								<td>
									<input type="text" id="client_phone" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_phone']. '"  />
								</td>		
							</tr>
							<tr>
								<td>დაბ. თარიღი</td>
								<td>
									<input type="text" id="born_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['born_date']. '"  />
								</td>
								<td>ელ-ფოსტა</td>
								<td>
									<input type="text" id="client_mail" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_mail']. '"  />
								</td>
							</tr>					
						</table>
					</fieldset>
					<fieldset style="width:665px; float:left;">
						<legend>მისამართი</legend>
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td style="font-weight:bold;">იურიდიული</td>
								<td></td>
								<td style="font-weight:bold;">ფაქტიური</td>
								<td></td>
							</tr>
							<tr>
								<td>მისამართი</td>
								<td>
									<input type="text" id="Juristic_address" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '"  />
								</td>
								<td>მისამართი</td>
								<td>
									<input type="text" id="physical_address" class="idle" onblur="this.className=\'idle\'"  value="' . $res['physical_address']. '"  />
								</td>			
							</tr>
							<tr>
								<td>ქალაქი</td>
								<td>
									<input type="text" id="Juristic_city" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_city']. '"  />
								</td>
								<td>ქალაქი</td>
								<td>
									<input type="text" id="physical_city" class="idle" onblur="this.className=\'idle\'"  value="' . $res['physical_city']. '"  />
								</td>			
							</tr>	
							<tr>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="Juristic_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_postal_code']. '"  />
								</td>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="physical_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['physical_postal_code']. '"  />
								</td>			
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>კოპირება</td>
								<td>
									<input type="checkbox" value="">
								</td>
							</tr>					
						</table>
						</fieldset>					
					';
												
		$data  .= '
		   
				<fieldset style="margin-top: 5px;">
			    	<legend>დავალების ფორმირება</legend>
		
			    	<table class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="d_number">დავალების ტიპი</label></td>
							<td style="width: 180px;"><label for="d_number">სცენარი</label></td>
							<td style="width: 180px;"><label for="d_number">პრიორიტეტი</label></td>
						</tr>
			    		<tr>
							<td style="width: 180px;"><select id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object">'. Get_template($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 627px; height: 80px; resize: none;" id="problem_comment" class="idle" name="content" cols="300" rows="2">' . $res['problem_comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div style="float: right; width: 450px;">
				<fieldset>
				<legend>საჩუქრები</legend>
				<div id="dt_example" class="inner-table">
		        <div style="width:440px;" id="container" >        	
		            <div id="dynamic">
		            	<div id="button_area">
		            		<button id="add_button_p">დამატება</button>
	        			</div>
		                <table class="" id="examplee" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">
										
		                           <th style="display:none">ID</th>
									<th style="width:9%;">#</th>
									<th style=" word-break:break-all;">თარიღი</th>
									<th style=" word-break:break-all;">პროდუქტი</th>
									<th style=" word-break:break-all;">თანხა</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 20px"/>
                            		</th>
									<th>
										<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									
									
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
				</fieldset>
				<div id="additional_info">
				<fieldset style="width: 440px;">
					<legend>შენაძენი</legend> 
					<table style="float: left; border: 1px solid #85b1de; width: 100%; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">#</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფილიალი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თარიღი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">პროდუქტი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თანხა</td>
						</tr>
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">1</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all"></td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all"></td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all"></td>	
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all"></td>		
						</tr>						
					<table/>
				</fieldset>
	  			<fieldset style="width: 440px;">
					<legend>საუბრის ჩანაწერი</legend> 
	  				<table style="float: left; border: 1px solid #85b1de; width: 250px; text-align: center; margin-left:100px;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; width:200px; color: #3C7FB1;">დრო</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; width:200px; color: #3C7FB1;">ჩანაწერი</td>
						</tr>
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">10:05:12 AM</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">მოსმენა</td>
	  					</tr>
					<table/>
				</fieldset>
						<input type="hidden" id="id" value="'.$_REQUEST['id'].'"/>		
			</div>
    </div>';

	return $data;
}

?>
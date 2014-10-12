<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
$hidden_id				= $_REQUEST['hidden_id'];

$incom_id				= $_REQUEST['id'];
$h_id					= $_REQUEST['h_id'];
$mont_date				= $_REQUEST['mont_date'];
//task
$task_type_id			= $_REQUEST['task_type_id'];
$template_id			= $_REQUEST['template_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];




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
		$page		= GetPage(Getincomming($incom_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT 	realizations.id,
										realizations.id,
										realizations.Date,
										realizations.CustomerName,
										SUM(nomenclature.Sum)AS sum_sale,
										realizations.Subdivision,
										realizations.StoreHouse,
										realizations.WaybillActivationDate,
										realizations.WaybillRecieveDate,
										realizations.instalation_date,
										realizations.WaybillStatus
								FROM 	realizations
								JOIN 	nomenclature ON nomenclature.realizations_id=realizations.id
								WHERE realizations.WaybillRecieveDate!=''
								GROUP BY realizations.id");
	  
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
	case 'save_sale':
		$hidden_id				= $_REQUEST['hidden_id'];
		Save_sale($mont_date);
		if ($hidden_id=='') {
			
			addtask($incom_id, $template_id, $priority_id, $task_type_id, $comment);
			
		}else {
			Savetask($incom_id, $template_id,  $priority_id, $task_type_id, $comment);
			}
		
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



function addtask($incom_id, $template_id, $priority_id, $task_type_id, $comment)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT  INTO `task`
								(`user_id`,`client_id`,`template_id`,`priority_id`,`task_type_id`,`comment`, `status`,`actived`)
						VALUES
								('$user','$incom_id','$template_id','$priority_id','$task_type_id','$comment','0','1')");
	
	
}

function Save_sale($mont_date)

		{
			$h_id				= $_REQUEST['h_id'];
			$user  = $_SESSION['USERID'];
			mysql_query("UPDATE realizations
							SET
								realizations.instalation_date='$mont_date'
								WHERE realizations.id=$h_id");
		}
function Savetask($incom_id, $template_id,  $priority_id, $task_type_id, $comment)
{

	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE task
				SET
						`user_id`='$user',
						`template_id`='$template_id',
						`priority_id`='$priority_id',
						`task_type_id`='$task_type_id',
						`comment`='$comment'
				WHERE `client_id`='$incom_id'");

}
function Get_template($task_department_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `template`
					    WHERE actived=1 ");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_department_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
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

function Get_sale($h_id)
{

	$req = mysql_query("SELECT	nomenclature.id as nomec_id,
			nomenclature.NomenclatureName,
			nomenclature.Sum
			from	nomenclature
			WHERE 	nomenclature.realizations_id = $h_id
			" );


	
	return $data;
}

function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("SELECT 	realizations.id,
												realizations.id,
												realizations.Date,
												realizations.CustomerName,
												realizations.Subdivision,
												realizations.StoreHouse,
												realizations.WaybillActivationDate,
												realizations.WaybillRecieveDate,
												realizations.WaybillStatus,
												realizations.CustomerID,
												realizations.CustomerName,
												realizations.CustomerAddress,
												realizations.CustomerPhone,	
												realizations.instalation_date,
												task.task_type_id,
												task.priority_id,
												task.template_id,
												task.`comment`,
												CASE WHEN SUM(`nomenclature`.`Sum`)>=5000 
														AND
														SUM(`nomenclature`.`Sum`)<7000
														THEN 'VIP Gold'
													 WHEN SUM(`nomenclature`.`Sum`)>=7000 
														AND
														SUM(`nomenclature`.`Sum`)<10000
														THEN 'VIP Platinium'
													WHEN SUM(`nomenclature`.`Sum`)>10000 
														THEN 'VIP Briliant'
													WHEN SUM(`nomenclature`.`Sum`)<5000 
														THEN 'ლოიალური'
												END AS `status`
								FROM 	realizations
										JOIN nomenclature ON nomenclature.realizations_id=realizations.id
										LEFT JOIN task ON task.client_id=realizations.id
										WHERE realizations.id =$incom_id
			" ));
	
	return $res;
}


function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}

	$increm = mysql_query("	SELECT  `name`,
									`rand_name`
							FROM 	`file`
							WHERE   `incomming_call_id` = $res[id]
			");
	
	$data  .= '
<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 400px;">	
				<fieldset >
			    	<legend>ძირითადი ინფორმაცია</legend>
					<fieldset style="width:300px; float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>ზედნადების #</td>
								<td>
									<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" value="' . $res['id']. '"disabled="disabled"/>
								</td>
							</tr>
							<tr>
								<td>ქვე-განყოფილება</td>
								<td>
									<input type="text" id="Subdivision" class="idle" onblur="this.className=\'idle\'" value="' . $res['Subdivision']. '"disabled="disabled"/>
								</td>
							</tr>
							<tr>
								<td>საწყობი</td>
								<td>
									<input type="text" id="CustomerName" class="idle" onblur="this.className=\'idle\'" value="' . $res['CustomerName']. '"disabled="disabled"/>
								</td>
							</tr>	
							<tr>
								<td></td>
								<td>
									
								</td>
							</tr>					
						</table>
					</fieldset>
					<fieldset style="width:300px; float:left; margin-left: 10px;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>შეძენის თარიღი</td>
								<td>
									<input type="text" id="Date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['WaybillActivationDate']. '"disabled="disabled"/>
								</td>
							</tr>
							<tr>
								<td>მიტანის თარიღი</td>
								<td>
									<input type="text" id="WaybillRecieveDate" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillRecieveDate']. '"disabled="disabled"/>
								</td>
							</tr>
							<tr>
								<td>მონტაჟის თარიღი</td>
								<td>
									<input type="text" id="mont_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['instalation_date']. '"/>
								</td>
							</tr>
							<tr>
								<td>სტატუსი</td>
								<td>
									<input type="text" id="WaybillStatus" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillStatus']. '"disabled="disabled"/>
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
								<textarea  style="width: 627px; height: 80px; resize: none;" id="comment" class="idle" name="content" cols="300" rows="2">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div style=" margin-left:690px;  width: 375px;">
				 <fieldset>
					<legend>კონტრაგენტი</legend>
					<table style="height: 163px;">						
						<tr>
							<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
							<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">'.$res['CustomerPhone'].'</td>
							<td style="width: 180px; color: #3C7FB1;">'.$res['CustomerID'].'</td>	
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">სახელი და გვარი</td>
							<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
						</tr>
						<tr >
							<td style="width: 180px;">'.$res['CustomerName'].'</td>
							<td style="width: 180px;">'.$res['CustomerAddress'].'</td>			
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
							<td td style="width: 180px; color: #3C7FB1;">სტატუსი</td>
						</tr>
						<tr>
							<td style="width: 180px;">'.$res['CustomerAddress'].'</td>
							<td td style="width: 180px;">'.$res['status'].'</td>
						</tr>
					</table>
				</fieldset>
				<div id="additional_info">
	  		</div>
			<div style="float: left;  width: 375px;">
				 <fieldset>
				<legend>შენაძენები</legend>
				<div id="dt_example" class="inner-table">
		        <div style="width:349px;" id="container" >        	
		            <div id="dynamic">
		            	<div id="button_area">
		            	</div>
		                <table class="" id="examplee_1" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">
										
		                           <th style="display:none">ID</th>
									<th style="width:15%;">თარიღი</th>
									<th style=" width:15%;">საწყობი</th>
									<th style="width:15%;">პროდუქტი</th>
									<th style="width:15%;">თანხა</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 66px"/>
                            		</th>
									<th>
										<input style="width:94px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:66px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
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
	  		</div>
					<table/>
	  				
			</div>
	  		<input type="hidden" id="h_id" value="'.$res['id'].'"/>
	  		<input type="hidden" id="hidden_id" value="'.$res['task_type_id'].'"/>
    </div>';
	return $data;
}


?>
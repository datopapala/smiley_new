<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

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
$client_comment			= $_REQUEST['client_comment'];

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
	case 'get_edit_page':
		$page		= GetPage(Getincomming($client_id));
		Get_sale();
		$data		= array('page'	=> $page);
		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
$rResult = mysql_query("SELECT DISTINCT `realizations`.`id`,
												`realizations`.`id`,
												`realizations`.`CustomerID`,
	  											`realizations`.`CustomerName`,
	  											`realizations`.`CustomerAddress`,
												`realizations`.`CustomerPhone`,
												'',
											  	SUM(`nomenclature`.`Sum`) AS jami,
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
										WHEN SUM(`nomenclature`.`Sum`)<5000 and COUNT(realizations.CustomerName)>5
											THEN 'ლოიალური'
									END AS `status`

									FROM 	`realizations`
									JOIN 	nomenclature ON realizations.id=nomenclature.realizations_id

	  								GROUP BY nomenclature.realizations_id
	  								HAVING SUM(`nomenclature`.`Sum`)>7000
	  										and
	  										SUM(`nomenclature`.`Sum`)<=10000
											AND (LENGTH(CustomerID)=11 OR CustomerID='') AND
											SUBSTRING(CustomerName,1,3)!='ი/მ' AND SUBSTRING(CustomerName,1,3)!='შპს' AND
											SUBSTRING(CustomerName,1,3)!='იმ.' AND SUBSTRING(CustomerName,1,3)!='ი.მ' AND
											SUBSTRING(CustomerName,1,3)!='ს/ს' AND SUBSTRING(CustomerName,1,3)!='სს ' AND
											SUBSTRING(CustomerName,1,3)!='ს.ს' AND SUBSTRING(CustomerName,1,3)!='შ.პ' AND
											SUBSTRING(CustomerName,1,3)!='იმ '
											");

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
		case 'save_client3':
			$client_id				= $_REQUEST['id'];
			if($client_id == ''){
				Addclient(  $client_name,  $client_status, $client_pin, $client_phone, $client_mail, $born_date, $client_mobile1, $client_mobile2, $Juristic_address, $Juristic_city,  $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code,$client_comment);
				$task_type_id = $_REQUEST['task_type_id'];
				if($task_type_id != ''){
					$incomming_call_id = mysql_insert_id();
					Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id, $problem_comment);

				}
			}else {
				Saveclient($client_name, $client_status, $client_pin, $born_date, $client_mobile1, $client_mobile2, $client_phone, $client_mail, $Juristic_address, $Juristic_city, $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code,$client_comment);
				Savetask();


			}
			break;

	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);
function Addclient(  $client_name,  $client_status, $client_pin, $client_phone, $client_mail, $born_date, $client_mobile1, $client_mobile2, $Juristic_address, $Juristic_city,  $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code,$client_comment){

	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];

	mysql_query("INSERT INTO `client`
	(`name`, `legal_status_id`, `code`, `phone`, `mail`, `born_date`, `mobile1`, `mobile2`, `Juristic_address`, `Juristic_city`, `Juristic_postal_code`, `physical_address`, `physical_city`, `physical_postal_code`,`comment`)
	VALUES
	( '$client_name', '$client_status', '$client_pin', '$client_phone', '$client_mail',' $born_date', '$client_mobile1', '$client_mobile2', '$Juristic_address', '$Juristic_city', '$Juristic_postal_code', '$physical_address', '$physical_city','$physical_postal_code','$client_comment');");

	//GLOBAL $log;
	//$log->setInsertLog('client');
}

function Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id, $problem_comment)
{

	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task`
	( `user_id`, `incomming_call_id`, `template_id`, `task_type_id`, `priority_id`,  `problem_comment`, `status`, `actived`)
	VALUES
	( '$user', '$incomming_call_id', '$template_id', '$task_type_id', '$priority_id',  '$problem_comment', '0', '1');");

	//GLOBAL $log;
	//$log->setInsertLog('task');
}



function Saveclient($client_name, $client_status, $client_pin, $born_date, $client_mobile1, $client_mobile2, $client_phone, $client_mail, $Juristic_address, $Juristic_city, $Juristic_postal_code, $physical_address, $physical_city, $physical_postal_code, $client_comment)
{

	$client_id				= $_REQUEST['id'];
	$user		= $_SESSION['USERID'];
	//GLOBAL $log;
	//$log->setUpdateLogAfter('client', $client_id);
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
									`physical_postal_code`='$physical_postal_code',
									`comment` = '$client_comment'
									WHERE			`id`='$client_id'
									");
	//$log->setInsertLog('client',$client_id);

}
function Savetask(){
	$id						= $_REQUEST['id'];
	$task_type_id			= $_REQUEST['task_type_id'];
	$template_id			= $_REQUEST['template_id'];
	$priority_id			= $_REQUEST['priority_id'];
	$problem_comment		= $_REQUEST['problem_comment'];
					//GLOBAL $log;
					//$log->setUpdateLogAfter('task', $id);

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

					//$log->setInsertLog('task',$id);
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

function Get_sale()
{

	$req = mysql_query("			SELECT			nomenclature.id as nom,
													realizations.Date as date,
													realizations.Subdivision,
													nomenclature.NomenclatureName,
													nomenclature.Sum
									from			realizations
									JOIN nomenclature ON realizations.id= nomenclature.realizations_id
									WHERE 			nomenclature.realizations_id =".$_REQUEST['id']."
			" );

	$data.='<fieldset style="width: 440px;">
					<legend>შენაძენი</legend>
					<table style="float: left; border: 1px solid #85b1de; width: 100%; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">#</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფილიალი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თარიღი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">პროდუქტი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თანხა</td>
						</tr>';
	while( $res1 = mysql_fetch_assoc($req)){
		$data .='
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['nom']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['Subdivision']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['date']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['NomenclatureName']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['Sum']. '</td>
						</tr>
							';
	};
	$data .='


					<table/>
				</fieldset>
								';
	return $data;
}

function Getincomming($client_id)
{

	$res = mysql_fetch_assoc(mysql_query("	SELECT
												realizations.id,
												realizations.CustomerName AS client_name,
												realizations.`CustomerID`,
												realizations.Date AS born_date,
												realizations.CustomerAddress AS Juristic_address,
												realizations.CustomerPhone,
												task.task_type_id AS task_type_id,
												task.template_id AS template_id,
												task.priority_id AS priority_id,
												task.problem_comment AS problem_comment,
										CASE
										 	WHEN  SUM(`nomenclature`.`Sum`)>5000
												AND
													 SUM(`nomenclature`.`Sum`)<=7000
												THEN 'VIP-Gold'
											WHEN  SUM(`nomenclature`.`Sum`)  >7000
												AND
													SUM(`nomenclature`.`Sum`)<=10000
												THEN 'VIP-Platinium'
											WHEN SUM(`nomenclature`.`Sum`) >10000
												THEN 'VIP-Briliant'
											WHEN SUM(`nomenclature`.`Sum`)<=5000 and COUNT(realizations.CustomerName)>5
												THEN 'ლოიალური'
									END AS `status`
								FROM 	realizations
										JOIN nomenclature ON realizations.id=nomenclature.realizations_id
										left JOIN    task ON realizations.id = task.incomming_call_id
										WHERE   realizations.id=$client_id

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
								<td>'.$res['status'].'</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>კონტრაგენტი</td>
								<td>
									<input type="text" id="client_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_name']. '" disabled="disabled" />
								</td>
								<td>მობილური 1</td>
								<td>
									<input type="text" id="client_mobile1" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '" disabled="disabled" />
								</td>

							</tr>
							<tr>
								<td>იურ. სტატუსი</td>
								<td>
									<select id="legal_status_id" class="idls object">'.Get_legal_status($res['legal_status_id']).'</select>
								</td>
								<td>მობილური 2</td>
								<td>
									<input type="text" id="client_mobile2" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '"disabled="disabled"  />
								</td>

							</tr>
							<tr>
								<td>პირადი ნომერი</td>
								<td>
									<input type="text" id="client_pin" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerID']. '"disabled="disabled"  />
								</td>
								<td>ტელეფონი</td>
								<td>
									<input type="text" id="client_phone" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '"disabled="disabled"  />
								</td>
							</tr>
							<tr>
								<td>დაბ. თარიღი</td>
								<td>
									<input type="text" id="born_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['born_date']. '"disabled="disabled"  />
								</td>
								<td>ელ-ფოსტა</td>
								<td>
									<input type="text" id="client_mail" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_mail']. '"disabled="disabled"  />
								</td>
							</tr>

						</tr>
						</table>
					</fieldset>
						<fieldset style="width:665px; float:left;">
						<legend>მოკლე შინაარსი</legend>
				    	<table width="100%" class="dialog-form-table">
							<tr>
							<td colspan="6">
								<textarea  style="width: 627px; height: 35px; resize: none;" id="client_comment" class="idle" name="content" cols="300" rows="2"disabled="disabled">' . $res['client_comment'] . '</textarea>
							</td>
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
									<input type="text" id="Juristic_address" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '"disabled="disabled"  />
								</td>
								<td>მისამართი</td>
								<td>
									<input type="text" id="physical_address" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td>ქალაქი</td>
								<td>
									<input type="text" id="Juristic_city" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '"disabled="disabled"  />
								</td>
								<td>ქალაქი</td>
								<td>
									<input type="text" id="physical_city" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '"disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="Juristic_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_postal_code']. '"disabled="disabled"  />
								</td>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="physical_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['physical_postal_code']. '" disabled="disabled" />
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
							<td style="width: 180px;"><select id="task_type_id" class="idls object"disabled="disabled">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object"disabled="disabled">'. Get_template($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object"disabled="disabled">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 627px; height: 45px; resize: none;" id="problem_comment" class="idle" name="content" cols="300" rows="2"disabled="disabled">' . $res['problem_comment'] . '</textarea>
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

	        			</div>
		                <table class="" id="examplee2" style="width: 100%;">
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
				<fieldset>
				<legend>საჩუქრები</legend>
				<div id="dt_example" class="inner-table">
		        <div style="width:440px;" id="container" >
		            <div id="dynamic">
		            	<div id="button_area">
		            	</div>
		                <table class="" id="examplee_4" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">

		                           <th style="display:none">ID</th>
									<th style="width:15%;">თარიღი</th>
									<th style=" word-break:break-all;">საწყობი</th>
									<th style=" word-break:break-all;">პროდუქტი</th>
									<th style=" word-break:break-all;">თანხა</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 50px"/>
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


						<input type="hidden" id="P_id" value="'.$res['id'].'"/>
			</div>
    </div>';

	return $data;
}
?>
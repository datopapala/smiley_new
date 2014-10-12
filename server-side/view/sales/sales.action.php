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
$incom_id				= $_REQUEST['id'];

$hh_id			= $_REQUEST['h_id'];




$mont_date			= $_REQUEST['mont_date'];
$task_type_id			= $_REQUEST['task_type_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];
$task_department_id 	= $_REQUEST['task_department_id'];


switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$h_id				= $_REQUEST['id'];
		$page		= GetPage(Getincomming($incom_id));
		Get_sale($h_id);
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
								WHERE realizations.WaybillRecieveDate=''
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
		save_sale($mont_date);
		
			Addtask($persons_id, $task_type_id,  $priority_id, $task_department_id,  $comment);
		
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


function Addtask($incomming_call_id, $persons_id, $task_type_id,  $priority_id, $task_department_id,  $comment)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO	`task` 
									(`user_id`,
									 `date`,
									 `responsible_user_id`,
									 `incomming_call_id`,
									 `task_type_id`,
									 `priority_id`,
									 `department_id`,
									 `phone`,
									 `comment`,
									 `problem_comment`)
						VALUES
									('$user',
									  NULL,
									 '$persons_id',
									 '$incomming_call_id',
									 '$task_type_id',
									 '$priority_id',
								     '$task_department_id',
								      NULL, 
								     '$comment', 
								     NULL)");
	
	
}
				
function Save_sale($mont_date)
{
	$hh_id			= $_REQUEST['h_id'];
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE realizations
					SET
						realizations.instalation_date='$mont_date'
						WHERE realizations.id=$hh_id");

}

function Getdepartment($task_department_id)
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


function Getpersons($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
							FROM `persons`
							WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
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

$data.='	<fieldset>
	<fieldset>
					<legend>შენაძენი</legend> 
					<table style="float: left; border: 1px solid #85b1de; width: 100%; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">#</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ნომენკლატურა</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფასი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">სხვა</td>
						</tr>';						 
						while( $res1 = mysql_fetch_assoc($req)){
						$data .='
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['nomec_id']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['NomenclatureName']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['Sum']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['']. '</td>							
						</tr>
							';
						};						
						$data .='	
	
	
					<table/>
				</fieldset>
								';
						return $data; 
}

function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	realizations.id,
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
												IF(realizations.instalation_date='0000-00-00 00:00:00','',realizations.instalation_date) AS instalation_date,			
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
										WHERE realizations.id = $incom_id
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
									<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" value="' . $res['id']. '"/>
								</td>
							</tr>
							<tr>
								<td>ქვე-განყოფილება</td>
								<td>
									<input type="text" id="Subdivision" class="idle" onblur="this.className=\'idle\'" value="' . $res['Subdivision']. '"/>
								</td>
							</tr>
							<tr>
								<td>საწყობი</td>
								<td>
									<input type="text" id="CustomerName" class="idle" onblur="this.className=\'idle\'" value="' . $res['CustomerName']. '"/>
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
									<input type="text" id="Date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['WaybillActivationDate']. '"/>
								</td>
							</tr>
							<tr>
								<td>მიტანის თარიღი</td>
								<td>
									<input type="text" id="WaybillRecieveDate" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillRecieveDate']. '"/>
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
									<input type="text" id="WaybillStatus" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillStatus']. '"/>
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
							<td style="width: 180px;" id="task_type_change"><select id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="task_department_id" class="idls object">'. Getdepartment($res['task_department_id']).'</select></td>
							<td style="width: 180px;"><select id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
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
		                <table class="" id="examplee_2" style="width: 100%;">
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
	  		<input type="hidden" id="hh_id" value="'.$res['id'].'"/>
    </div>';

	return $data;
}

?>
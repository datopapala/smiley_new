<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';




switch ($action) {
	case 'get_edit_page':
		$page		= GetPage(Getincomming());
		Get_sale();
		$data		= array('page'	=> $page);
	
		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT DISTINCT `realizations`.`id`,
												`realizations`.`id`,
												`realizations`.`CustomerID`,
	  											`realizations`.`Customer1CCode`,
												`realizations`.`CustomerName`,
												`realizations`.`CustomerPhone`,
												`realizations`.`CustomerAddress`,
												COUNT(realizations.CustomerName),
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
										WHEN SUM(`nomenclature`.`Sum`)<5000 
											THEN 'ლოიალური'
									END AS `status`
												
									FROM 	`realizations`
									JOIN 	nomenclature ON realizations.id=nomenclature.realizations_id
								
	  							GROUP BY nomenclature.realizations_id
	  							HAVING SUM(`nomenclature`.`Sum`)<5000		");
  
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
	
	
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);

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

function Getincomming()
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
											WHEN SUM(`nomenclature`.`Sum`)<=5000
												THEN 'ლოიალური'
									END AS `status`
								FROM 	realizations
										JOIN nomenclature ON realizations.id=nomenclature.realizations_id
										left JOIN    task ON realizations.id = task.incomming_call_id
										WHERE   realizations.id=".$_REQUEST['id']."

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
			<div style="float: left; width: 500px;" >
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
									<input type="text" id="client_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['client_name']. '"  disabled="disabled" />
								</td>
								<td>მობილური 1</td>
								<td>
									<input type="text" id="client_mobile1" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '"  disabled="disabled" />
								</td>
						
							</tr>
							<tr>
								<td>იურ. სტატუსი</td>
								<td>
									<select id="legal_status_id" class="idls object" disabled="disabled">'.Get_legal_status($res['legal_status_id']).'</select>
								</td>
								<td>მობილური 2</td>
								<td>
									<input type="text" id="client_mobile2" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '"  disabled="disabled" />
								</td>

							</tr>
							<tr>
								<td>პირადი ნომერი</td>
								<td>
									<input type="text" id="client_pin" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerID']. '" disabled="disabled"  />
								</td>
								<td>ტელეფონი</td>
								<td>
									<input type="text" id="client_phone" class="idle" onblur="this.className=\'idle\'"  value="' . $res['CustomerPhone']. '"  disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td>დაბ. თარიღი</td>
								<td>
									<input type="text" id="born_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['born_date']. '" disabled="disabled" />
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
									<input type="text" id="physical_city" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_address']. '" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="Juristic_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Juristic_postal_code']. '" disabled="disabled" />
								</td>
								<td>საფოსტო კოდი</td>
								<td>
									<input type="text" id="physical_postal_code" class="idle" onblur="this.className=\'idle\'"  value="' . $res['physical_postal_code']. '" disabled="disabled" />
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
		                <table class="" id="examplee4" style="width: 100%;">
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
				';

	$data.=	Get_sale($res['id']);
	$data .='	<table/>


						<input type="hidden" id="id" value="'.$_REQUEST['id'].'"/>
			</div>
    </div>';

	return $data;
}
?>
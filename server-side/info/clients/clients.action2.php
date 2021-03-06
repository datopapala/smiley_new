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
$call_date				= $_REQUEST['call_date'];
$site_user_pin			= $_REQUEST['pin'];
$call_type_id			= $_REQUEST['call_type_id'];
$phone					= $_REQUEST['phone'];
$category_id			= $_REQUEST['category_id'];
$problem_date			= $_REQUEST['problem_date'];
$call_content			= $_REQUEST['call_content'];
$category_parent_id 	= $_REQUEST['category_parent_id'];
$call_status_id			= $_REQUEST['call_status_id'];

$pay_type_id			= $_REQUEST['pay_type_id'];
$bank_id				= $_REQUEST['bank_id'];
$card_type_id			= $_REQUEST['card_type_id'];
$pay_aparat_id			= $_REQUEST['pay_aparat_id'];
$object_id				= $_REQUEST['object_id'];


// site_user
$personal_pin			= $_REQUEST['personal_pin'];
$personal_id			= $_REQUEST['personal_id'];
$personal_phone			= $_REQUEST['personal_phone'];
$mail				    = $_REQUEST['mail'];
$name				    = $_REQUEST['name'];
$friend_pin				= $_REQUEST['friend_pin'];

//task
$persons_id			    = $_REQUEST['persons_id'];
$task_type_id			= $_REQUEST['task_type_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];
$task_department_id 	= $_REQUEST['task_department_id'];
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
		$page		= GetPage(Getincomming($incom_id));

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
								HAVING SUM(`nomenclature`.`Sum`)<5000
	  							GROUP BY nomenclature.realizations_id
	  								
	  							
												
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
	
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);




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


function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT    	incomming_call.id AS id,
													incomming_call.phone AS `phone`,
													DATE_FORMAT(incomming_call.`date`,'%d-%m-%y %H:%i:%s') AS call_date,
													incomming_call.call_type_id AS call_type_id,
													incomming_call.call_category_id AS category_id,
													IF(ISNULL(task.`status`), 3, task.`status`) AS `status`,
													incomming_call.call_subcategory_id AS category_parent_id,
													DATE_FORMAT(incomming_call.`date`,'%d-%m-%y %H:%i:%s') AS problem_date,
													incomming_call.call_content AS call_content,
													incomming_call.pay_type_id AS pay_type_id,
													incomming_call.bank_id AS bank_id,
													incomming_call.bank_object_id AS bank_object_id,
													incomming_call.card_type_id AS card_type_id,
													incomming_call.card_type_id AS card_type1_id,
													incomming_call.pay_aparat_id AS pay_aparat_id,
													incomming_call.object_id AS object_id,
													site_user.`name` AS `name`,
													site_user.mail AS mail,
													site_user.personal_id AS personal_id,
													site_user.phone AS personal_phone,
													site_user.pin AS personal_pin,
													site_user.friend_pin AS friend_pin,
													site_user.`name` AS `name1`,
													site_user.`mail` AS `mail`,
													site_user.user AS user,
													task.task_type_id AS task_type_id,
													task.responsible_user_id AS persons_id,
													task.priority_id AS priority_id,
													task.department_id AS task_department_id,
													task.`comment` AS `comment`
										FROM 	   	incomming_call
										LEFT JOIN  	site_user ON incomming_call.id=site_user.incomming_call_id
										LEFT JOIN  	task  ON incomming_call.id=task.incomming_call_id
										where      	incomming_call.id = $incom_id
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
			<div style="float: left; width: 800px;">	
				<fieldset >
			    	<legend>ძირითადი ინფორმაცია</legend>
		
			    	<table width="100%" class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="">მომართვა №</label></td>
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;"><label for="phone">ტელეფონი</label></td>
							<td></td>
							<td><label for="person_name">აბონენტის სახელი</label></td>
						</tr>
						<tr>
							<td style="width: 180px;">
								<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField user_id\'" value="' . $res['id']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField date\'" value="' .  $res['call_date']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $num . '" />
							</td>
							<td style="width: 69px;">
								<button class="calls">ნომრები</button>
							</td>
							<td style="width: 69px;">
								<input type="text" id="person_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $num . '" />
							</td>
						</tr>						
					</table>';
										
		$data  .= '
				
				<fieldset style="width:318px; float:left;">
			    	<legend>მომართვის ავტორი</legend>
					<table id="additional" class="dialog-form-table" width="300px">						
						<tr>
							<td style="width: 250px;"><input style="float:left;" type="radio" value="1"><span style="margin-top:5px; display:block;">ფიზიკური</span></td>
							<td style="width: 250px;"><input style="float:left;" type="radio" value="2"><span style="margin-top:5px; display:block;"">იურიდიული</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:400px; float:left; margin-left: 15px;">
			    	<legend>ინფორმაციის კატეგორია</legend>
					<table id="additional" class="dialog-form-table" width="230px">						
						<tr>
							<td style="width: 300px;"><select style="margin-left: 25px;" id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:755px; float:left;">
			    	<legend>ინფორმაციის კატეგორია</legend>
					<table id="additional" class="dialog-form-table" width="230px">		
						<tr>
							<td style="width: 250px;"><input style="float:left;" type="radio" value="1"><span style="margin-top:5px; display:block;">ფიზიკური</span></td>
							<td style="width: 250px;"><input style="float:left; margin-left: 20px;" type="radio" value="2"><span style="margin-top:5px; display:block;"">იურიდიული</span></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">შეძენის თარიღი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">კატეგორია</label></td>
						</tr>
						<tr>
							<td style="width: 300px;"><label for="d_number">პროდუქტი</label></td>
							<td style="width: 300px;"><label style="margin-left: 15px;" for="d_number">ბრენდი</label></td>
							<td style="width: 250px;"><input style="margin-left: 25px;" type="text"  id="problem_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res[problem_date] . '" /></td>
							<td style="width: 250px;"><select style="margin-left: 25px;" id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
						</tr>				
						<tr>
							<td style="width: 300px;"><select id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:755px; float:left;">
			    	<legend>გადამისამართება</legend>
					<table id="additional" class="dialog-form-table" width="230px">		
						<tr>
							<td style="width: 300px;"><label for="d_number">ქვე-განყოფილება</label></td>
							<td style="width: 300px;"><label style="margin-left: 35px;" for="d_number">კავშირი</label></td>
						</tr>
						<tr>
							<td style="width: 250px;"><select style=" width: 450px;" id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
							<td style="width: 250px;"><input style="margin-left: 35px;" type="radio" value="1"></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:160px; float:left;">
			    	<legend>რეაგირება</legend>
					<table id="additional" class="dialog-form-table" width="150px">	
						<tr>
							<td style="width: 150px;"><select id="object_id" class="idls object">'. Getobject($res['object_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:557px; float:left; margin-left: 10px;">
			    	<legend>რეაგირება</legend>
					<table id="additional" class="dialog-form-table" width="150px">	
						<tr>
							<td><textarea  style="width: 550px; resize: none;" id="comment" class="idle" name="content" cols="300" >' . $res['comment'] . '</textarea></td>
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
								<textarea  style="width: 747px; resize: none;" id="comment" class="idle" name="content" cols="300" rows="2">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div style="float: right;  width: 355px;">
				 <fieldset>
					<legend>მომართვის ავტორი</legend>
					<table style="height: 243px;">						
						<tr>
							<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
							<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
						</tr>
						<tr>
							<td>568919432</td>
							<td style="width: 180px;">
								<input type="text" id="personal_id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
							</td>					
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">კონტრაგენტი</td>
							<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
						</tr>
						<tr >
							<td style="width: 180px;">ზაზა მესხი</td>
							<td style="width: 180px;">z.mesxi@yahoo.com</td>			
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
							<td td style="width: 180px; color: #3C7FB1;">სტატუსი</td>
						</tr>
						<tr>
							<td style="width: 180px;">ყვარლის 149</td>
							<td td style="width: 180px;">VIP კლიენტი</td>
						</tr>
					</table>
				</fieldset>
				<div id="additional_info">';
					if (!empty($res['personal_pin'])){
							$data .= get_addition_all_info($res['personal_pin']);
						}
	  $data .= '</div>
				<fieldset>
					<legend>შენაძენი</legend> 
					<table style="float: left; border: 1px solid #85b1de; width: 153px; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px;"></td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფილიალი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თარიღი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">პროდუქტი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თანხა</td>
						</tr>
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">1</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">gelaaaaaaaaaaaaaaaa</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">2014-07-01</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">fssdgsd</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">145$</td>
							
						</tr>
						
					<table/>
				</fieldset>
	  			<fieldset>
					<legend>საუბრის ჩანაწერი</legend> 
	  				<table style="float: left; border: 1px solid #85b1de; width: 250px; text-align: center; margin-left:40px;">
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